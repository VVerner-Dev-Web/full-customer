<?php

namespace Full\Customer;

use PhpZip\ZipFile;

defined('ABSPATH') || exit;

class FileSystem
{
  private const TEMPORARY_DIR = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'full-temporary';

  public function scanDir(string $path): array
  {
    $path  = trailingslashit(realpath($path));
    $path  = str_replace(['\\', '/'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $path);

    return glob($path . '{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE);
  }

  public function createTemporaryDirectory(): void
  {
    if (is_dir(self::TEMPORARY_DIR)) :
      $this->deleteDirectory(self::TEMPORARY_DIR, true);
    endif;

    mkdir(self::TEMPORARY_DIR);
  }

  public function deleteTemporaryDirectory(): void
  {
    $this->deleteDirectory(self::TEMPORARY_DIR);
  }

  public function getTemporaryDirectoryPath(): string
  {
    return self::TEMPORARY_DIR;
  }

  public function moveFile(string $originPath, string $destinationPath, bool $deleteIfExists = true): bool
  {
    $exists = is_dir($destinationPath);

    if ($exists && !$deleteIfExists) :
      return false;

    elseif ($exists) :
      $this->deleteDirectory($destinationPath);

    endif;

    return rename(
      $originPath,
      $destinationPath
    );
  }

  public function copyFile(string $originPath, string $destinationPath): bool
  {
    return @copy(
      $originPath,
      $destinationPath
    );
  }

  public function extractZip(string $zipFilePath, string $destinationPath, bool $deleteAfterExtract = true): bool
  {
    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    $zipFile = new ZipFile();

    $zipFile->openFile($zipFilePath)->extractTo($destinationPath)->close();

    if ($deleteAfterExtract) :
      unlink($zipFilePath);
    endif;

    return true;
  }

  public function createZip(string $sourcePath, string $outputZipPath)
  {
    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    $zipFile = new ZipFile();
    $zipFile->addDirRecursive($sourcePath, '', \PhpZip\Constants\ZipCompressionMethod::DEFLATED)->saveAsFile($outputZipPath)->close();
  }

  public function deleteDirectory(string $path): bool
  {
    $files = $this->scanDir($path);

    foreach ($files as $file) :
      is_dir($file) ? $this->deleteDirectory($file) : $this->deleteFile($file);
    endforeach;

    return @rmdir($path);
  }

  public function deleteFile(string $path): bool
  {
    return @unlink(realpath($path));
  }
}