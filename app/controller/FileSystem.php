<?php

namespace Full\Customer;

use ZipArchive;

defined('ABSPATH') || exit;

class FileSystem
{
  private const TEMPORARY_DIR = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'full-temporary';

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

  public function extractZip(string $zipFilePath, string $destinationPath): bool
  {
    $worker = new ZipArchive;
    $opened = $worker->open($zipFilePath);

    if ($opened !== true) :
      unlink($zipFilePath);
      return false;
    endif;

    $worker->extractTo($destinationPath);
    $worker->close();

    unlink($zipFilePath);

    return true;
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

  private function deleteDirectory(string $path): void
  {
    $path  = trailingslashit(realpath($path));
    $path  = str_replace(['\\', '/'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $path);
    $files = glob($path . '{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE);

    foreach ($files as $file) :
      is_dir($file) ? $this->deleteDirectory($file) : $this->deleteFile($file);
    endforeach;

    @rmdir($path);
  }

  private function deleteFile(string $path): void
  {
    @unlink(realpath($path));
  }
}
