<?php

namespace Full\Customer;

use Exception;
use PhpZip\ZipFile;

defined('ABSPATH') || exit;

class FileSystem
{
  private const TEMPORARY_DIR = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'full-temporary';

  public function getHumanReadableFileSize(int $fileSize): string
  {
    $label  = ['b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb'];
    $factor = floor((strlen($fileSize) - 1) / 3);
    $factorSz = isset($label[$factor]) && $label[$factor] ? $label[$factor] : 'b';
    return sprintf('%.0f', $fileSize / pow(1024, $factor)) . $factorSz;
  }

  public function scanDir(string $path): array
  {
    $path  = trailingslashit(realpath($path));
    $path  = str_replace(['\\', '/'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $path);
    $flags = defined('GLOB_BRACE') ? GLOB_MARK | GLOB_BRACE : 0;

    return glob($path . '{,.}[!.,!..]*', $flags);
  }

  public function createTemporaryDirectory(): void
  {
    if (is_dir($this->getTemporaryDirectoryPath())) :
      $this->deleteTemporaryDirectory();
    endif;

    if (is_dir($this->getTemporaryDirectoryPath())) :
      throw new Exception('Não foi possível apagar todo o temp dir');
    endif;

    fullFileSystem()->mkdir($this->getTemporaryDirectoryPath());
  }

  public function deleteTemporaryDirectory(): void
  {
    $this->deleteDirectory($this->getTemporaryDirectoryPath());
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

    return fullFileSystem()->move($originPath, $destinationPath, true);
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
      set_time_limit(600);
    endif;

    $zipFile = new ZipFile();

    $zipFile->openFile($zipFilePath)->extractTo($destinationPath)->close();

    if ($deleteAfterExtract) :
      wp_delete_file($zipFilePath);
    endif;

    return true;
  }

  public function createZip(string $sourcePath, string $outputZipPath): void
  {
    if (function_exists('set_time_limit')) :
      set_time_limit(600);
    endif;

    $zipFile = new ZipFile();
    $zipFile->addDirRecursive($sourcePath, '', \PhpZip\Constants\ZipCompressionMethod::DEFLATED)->saveAsFile($outputZipPath)->close();
  }

  public function deleteDirectory(string $path): bool
  {
    return fullFileSystem()->rmdir($path, true);
  }

  public function deleteFile(string $path): bool
  {
    return wp_delete_file($path);
  }

  public function downloadExternalResource(string $source, string $filename): string
  {
    $this->createTemporaryDirectory();

    $path = $this->getTemporaryDirectoryPath() . DIRECTORY_SEPARATOR . $filename;

    if (file_exists($path)) :
      wp_delete_file($path);
    endif;

    $file = fopen($path, 'a');
    fclose($file);

    wp_remote_get($source, [
      'sslverify' => false,
      'stream'    => true,
      'filename'  => $path
    ]);

    return $path;
  }
}
