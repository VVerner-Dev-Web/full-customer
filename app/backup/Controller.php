<?php

namespace Full\Customer\Backup;

use Full\Customer\FileSystem;

class Controller
{
  private $instanceId = null;

  private const STOP_WORDS  = ['cache', 'backup', 'upgrade', 'temp', '-old', 'backups', 'log', '-restore-'];

  public function __construct()
  {
    global $wpdb;

    $this->db = $wpdb;
    $this->fs = new FileSystem();
  }

  public function createBackup(): int
  {
    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    $this->fs->createTemporaryDirectory();

    $dirs     = $this->getDirectoriesToBackup();

    $backupId = 'backup-' . current_time('YmdHis');
    $zipDir   = trailingslashit($this->fs->getTemporaryDirectoryPath());
    $zipFile  = $this->getBackupFile($backupId);

    foreach ($dirs as $dir) :
      if (is_dir($dir)) :
        $this->fs->createZip($dir, $zipDir . basename($dir) . '.zip');
      else :
        $this->fs->copyFile(basename($dir), $zipDir . basename($dir));
      endif;
    endforeach;

    $mysqlFile = $zipDir . 'db.sql';
    $mysql     = new MysqlDump();

    $mysql->export($mysqlFile);

    $this->fs->createZip(untrailingslashit($zipDir), $zipFile);

    $this->fs->deleteTemporaryDirectory();

    return (int) preg_replace('/\D/', '', $backupId);
  }

  public function getBackups(): array
  {
    $backups = [];

    foreach ($this->fs->scanDir($this->getBackupDirectory()) as $file) :
      if (substr($file, -4) !== '.zip') :
        continue;
      endif;

      $backups[] = [
        'backup_id'   => (int) preg_replace('/\D/', '', $file)
      ];
    endforeach;

    return $backups;
  }

  public function deleteBackup(string $backupId): bool
  {
    $file = $this->getBackupFile($backupId);
    return file_exists($file) ? $this->fs->deleteFile($file) : false;
  }

  public function restoreBackup(string $backupId): bool
  {
    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    $this->instanceId = 'restore-' . current_time('Ymdhis');
    $backupFile       = $this->getBackupFile($backupId);

    if (!file_exists($backupFile)) :
      return false;
    endif;

    $this->fs->createTemporaryDirectory();
    $restoreDirectory = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $backupId . DIRECTORY_SEPARATOR;

    if (!$this->fs->extractZip($backupFile, $this->fs->getTemporaryDirectoryPath(), false)) :
      return false;
    endif;

    $this->fs->moveFile(
      WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'full-temporary',
      $restoreDirectory
    );

    $backupArchives   = $this->fs->scanDir($restoreDirectory);

    foreach ($backupArchives as $item) :
      if (substr($item, -4) === '.sql') :
        $this->restoreDatabase($item);

      elseif (substr($item, -4) === '.zip') :
        $this->restoreDirectory($item);

      elseif (is_file($item)) :
        $this->restoreFile($item);

      endif;
    endforeach;

    $this->fs->deleteDirectory($restoreDirectory);

    return true;
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
        $restoreDirectory . DIRECTORY_SEPARATOR . $directory,
        $wpDirectory
      );
    endif;
  }

  private function moveToRestoreBackup(string $directoryToBackup): void
  {
    $this->fs->moveFile(
      $directoryToBackup,
      untrailingslashit($directoryToBackup) . '-' . $this->instanceId
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

  private function getDirectoriesToBackup(): array
  {
    $dirs = [];

    $items = $this->fs->scanDir(WP_CONTENT_DIR);

    foreach ($items as $path) :
      foreach (self::STOP_WORDS as $word) :
        if (strpos($path, $word) !== false) :
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

  private function getBackupFile(string $backupId): ?string
  {
    return $this->getBackupDirectory() . $backupId . '.zip';
  }
}
