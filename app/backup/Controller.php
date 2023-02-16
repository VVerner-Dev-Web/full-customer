<?php

namespace Full\Customer\Backup;

use Full\Customer\FileSystem;
use FullCustomer;

class Controller
{
  private $instanceId = null;
  private $fs;

  private const STOP_WORDS  = ['cache', 'backup', 'upgrade', 'temp', '-old', 'backups', 'log', '-restore-'];
  private const LOCK_OPTION = '_full_backup_class_locked';

  public function __construct()
  {
    $this->fs = new FileSystem();
  }

  public function createAsyncBackup(): int
  {
    $cron = new Cron();
    $cron->enqueueAsyncCreateHook();
    return 0;
  }

  public function createBackup(): int
  {
    if ($this->isLocked()) :
      return 0;
    endif;

    $this->lockClass();

    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    $this->fs->createTemporaryDirectory();

    $items    = $this->getItemsToBackup();

    $backupId = 'backup-' . current_time('YmdHis');
    $zipDir   = trailingslashit($this->fs->getTemporaryDirectoryPath());
    $zipFile  = $this->getBackupFile($backupId);

    foreach ($items as $item) :
      if (is_dir($item)) :
        $this->fs->createZip($item, $zipDir . basename($item) . '.zip');
      elseif (is_file($item)) :
        $this->fs->copyFile($item, $zipDir . basename($item));
      endif;
    endforeach;

    $mysql     = new MysqlDump();
    $mysql->export($zipDir . 'db.sql');

    $this->fs->createZip(untrailingslashit($zipDir), $zipFile);
    $this->fs->deleteTemporaryDirectory();

    $this->deleteOldBackups();

    $this->unlockClass();

    $backup = (int) preg_replace('/\D/', '', $backupId);

    $this->triggerWebhookEvent('backup:created-success', ['backup' => $backup]);

    return $backup;
  }

  public function getBackups(): array
  {
    $backups = [];

    foreach ($this->fs->scanDir($this->getBackupDirectory()) as $file) :
      if (substr($file, -4) !== '.zip') :
        continue;
      endif;

      $backups[] = $this->normalizeBackupData($file);
    endforeach;

    usort($backups, function ($a, $b) {
      return $b['dateU'] <=> $a['dateU'];
    });

    return $backups;
  }

  private function normalizeBackupData(string $file): ?array
  {
    if (!file_exists($file)) :
      return null;
    endif;

    $size = filesize($file);

    return [
      'id'         => (int) preg_replace('/\D/', '', basename($file)),
      'sizeLegend' => $this->fs->getHumanReadableFileSize($size),
      'size'       => $size,
      'dateGtm'    => date('Y-m-d H:i:s', filemtime($file) - HOUR_IN_SECONDS * 3),
      'dateU'      => filemtime($file)
    ];
  }

  public function getBackup(string $backupId): ?array
  {
    $file = $this->getBackupFile($backupId);
    return $file ? $this->normalizeBackupData($file) : null;
  }

  public function deleteBackup(string $backupId): bool
  {
    $file = $this->getBackupFile($backupId);
    return file_exists($file) ? $this->fs->deleteFile($file) : false;
  }

  public function restoreAsyncBackup(string $backupId, string $remoteBackupFile, string $remoteBackupId): bool
  {
    $cron = new Cron();
    $cron->enqueueAsyncRestoreHook($backupId, $remoteBackupFile, $remoteBackupId);
    return true;
  }

  public function restoreBackup(string $backupId, string $remoteBackupFile, string $remoteBackupId): bool
  {
    $this->unlockClass();

    if ($this->isLocked()) :
      return false;
    endif;

    $this->lockClass();

    if ($remoteBackupFile && $remoteBackupId) :
      $backupFile = $this->fs->downloadExternalResource($remoteBackupFile, $backupId);

      $this->fs->moveFile(
        $backupFile,
        $this->getBackupDirectory() . $remoteBackupId
      );

      $backupId = str_replace('.zip', '', $remoteBackupId);
    endif;

    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    $this->instanceId = '-restore-' . current_time('Ymdhis');
    $backupFile       = $this->getBackupFile($backupId);

    if (!file_exists($backupFile)) :
      $this->unlockClass();
      return false;
    endif;

    $this->fs->createTemporaryDirectory();
    $restoreDirectory = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $backupId . DIRECTORY_SEPARATOR;

    if (!$this->fs->extractZip($backupFile, $this->fs->getTemporaryDirectoryPath(), false)) :
      $this->unlockClass();
      return false;
    endif;

    $this->fs->moveFile(
      WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'full-temporary',
      $restoreDirectory
    );

    foreach ($this->fs->scanDir($restoreDirectory) as $item) :
      if (substr($item, -4) === '.sql') :
        $this->restoreDatabase($item);

      elseif (substr($item, -4) === '.zip') :
        $this->restoreDirectory($item);

      elseif (is_file($item)) :
        $this->restoreFile($item);

      endif;
    endforeach;

    $this->fs->deleteDirectory($restoreDirectory);
    $this->fs->deleteTemporaryDirectory();

    $this->triggerWebhookEvent('backup:restore-success');

    $this->unlockClass();
    return true;
  }

  public function getBackupFile(string $backupId): ?string
  {
    return $this->getBackupDirectory() . $backupId . '.zip';
  }

  private function triggerWebhookEvent(string $event, array $data = []): void
  {
    $full     = new FullCustomer();
    $endpoint = 'backup:created-success' === $event ? 'backup-webhook' : 'restore-webhook';
    $url      = $full->getFullDashboardApiUrl() . '-customer/v1/' . $endpoint;

    wp_remote_post($url, [
      'sslverify' => false,
      'headers'   => [
        'Content-Type'  => 'application/json',
      ],
      'body'      => json_encode(array_merge(['site_url' => home_url()], $data))
    ]);
  }

  private function restoreFile(string $backupFile): void
  {
    $filename = basename($backupFile);
    $wpFile   = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $filename;

    if (file_exists($wpFile)) :
      $this->moveToRestoreBackup($wpFile);
    endif;

    $this->fs->moveFile(
      $backupFile,
      $wpFile
    );
  }

  private function restoreDirectory(string $backupFile): void
  {
    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    $this->fs->createTemporaryDirectory();

    $restoreDirectory = $this->fs->getTemporaryDirectoryPath();
    $directory        = str_replace('.zip', '', basename($backupFile));
    $wpDirectory      = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $directory;

    if ($this->fs->extractZip($backupFile, $restoreDirectory)) :
      if (is_dir($wpDirectory)) :
        $this->moveToRestoreBackup($wpDirectory, $directory);
      endif;

      $this->fs->moveFile(
        $restoreDirectory,
        $wpDirectory
      );
    endif;
  }

  private function moveToRestoreBackup(string $directoryToBackup): void
  {
    $this->fs->moveFile(
      $directoryToBackup,
      untrailingslashit($directoryToBackup) . $this->instanceId,
      false
    );
  }

  private function restoreDatabase(string $sqlFile): void
  {
    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    $mysql  = new MysqlDump();
    $mysql->import($sqlFile);
  }

  private function getItemsToBackup(): array
  {
    $dirs = [];

    foreach ($this->fs->scanDir(WP_CONTENT_DIR) as $path) :
      foreach (self::STOP_WORDS as $word) :
        if (strpos(basename($path), $word) !== false) :
          continue 2;
        endif;
      endforeach;

      $dirs[] = $path;
    endforeach;

    return $dirs;
  }

  private function getBackupDirectory(): string
  {
    $dir = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'full-backups' . DIRECTORY_SEPARATOR;

    if (!is_dir($dir)) :
      mkdir($dir);
    endif;

    return $dir;
  }

  private function lockClass(): void
  {
    set_transient(self::LOCK_OPTION, true, HOUR_IN_SECONDS * 6);
  }

  private function unlockClass(): void
  {
    delete_transient(self::LOCK_OPTION);
  }

  private function isLocked(): bool
  {
    return get_transient(self::LOCK_OPTION) ? true : false;
  }

  public function deleteOldBackups()
  {
    $cron     = new Cron();
    $backups  = $this->getBackups();
    $deletableBackups = $cron->getBackupsQuantityToMaintain() > 0 ? count($backups) - $cron->getBackupsQuantityToMaintain() : 0;

    if (0 >= $deletableBackups) :
      return;
    endif;

    for ($i = 0; $i < $deletableBackups; $i++) :
      $item = array_pop($backups);
      $file = $this->getBackupFile('backup-' . $item['id']);

      $this->fs->deleteFile($file);
    endfor;
  }
}
