<?php

namespace FC;

use WP_Filesystem_Base;

final class FileSystem
{
  private static ?FileSystem $instance = null;
  private string $baseUrl;

  public function wpContentDir(): string
  {
    return trailingslashit($this->core()->wp_content_dir());
  }

  public static function instance(): self
  {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function resolvePath(string $path): string
  {
    $path = strpos($path, 'wp-content') === false ?
      rtrim(FULL_CUSTOMER_PATH, '/') . '/' . ltrim($path, '/')
      : $path;

    $path = str_replace(
      ['/', '\\'],
      [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR],
      $path
    );

    return $path;
  }

  public function getUrl(string $relativePath): string
  {
    if (!isset($this->baseUrl)) {
      $this->baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE));
    }

    return strpos($relativePath, 'https://') === false ?
      $this->baseUrl . ltrim($relativePath, '/')
      : $relativePath;
  }

  public function getContents(string $relativePath)
  {
    $fulllPath = $this->resolvePath($relativePath);
    return $this->core()->get_contents($fulllPath);
  }

  public function include(string $relativePath)
  {
    $fulllPath = $this->resolvePath($relativePath);
    $this->isFile($relativePath) && include $fulllPath;
  }

  public function delete(string $relativePath, bool $recursive = false)
  {
    $fulllPath = $this->resolvePath($relativePath);
    return $this->core()->delete($fulllPath, $recursive);
  }

  public function putContents(string $relativePath, $contents, $mode = false)
  {
    $fulllPath = $this->resolvePath($relativePath);
    return $this->core()->put_contents($fulllPath, $contents, $mode);
  }

  public function isFile(string $relativePath): bool
  {
    $fulllPath = $this->resolvePath($relativePath);
    return $this->core()->is_file($fulllPath);
  }

  public function isDir(string $relativePath): bool
  {
    $fulllPath = $this->resolvePath($relativePath);
    return $this->core()->is_dir($fulllPath);
  }

  public function modifiedTime(string $relativePath): int
  {
    $fulllPath = $this->resolvePath($relativePath);
    return $this->core()->mtime($fulllPath);
  }

  public function downloadWithProgress(string $url, string $filepath, callable $progressCallback): bool|\WP_Error
  {
    $fp = fopen($filepath, 'w+');
    if (!$fp) {
      return new \WP_Error('file_write_error', 'Não foi possível criar o arquivo temporário no disco.');
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300); // 5 minutes
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);

    $last_update = 0;

    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($ch, $download_size, $downloaded, $upload_size, $uploaded) use ($progressCallback, &$last_update) {
      if ($download_size > 0) {
        $now = microtime(true);
        if ($now - $last_update >= 1.0 || $downloaded === $download_size) {
          $last_update = $now;
          $percent = round(($downloaded / $download_size) * 100);
          call_user_func($progressCallback, 'percent', $percent);
        }
      } elseif ($downloaded > 0) {
        $now = microtime(true);
        if ($now - $last_update >= 1.0) {
          $last_update = $now;
          $mb = round($downloaded / (1024 * 1024), 2);
          call_user_func($progressCallback, 'size', $mb);
        }
      }
      return 0;
    });

    $success = curl_exec($ch);
    $error_msg = curl_error($ch);
    curl_close($ch);
    fclose($fp);

    if (!$success) {
      return new \WP_Error('download_failed', 'Erro no download: ' . $error_msg);
    }

    return true;
  }

  public function mkdir(string $relativePath): bool
  {
    $fullPath = $this->resolvePath($relativePath);
    return wp_mkdir_p($fullPath);
  }

  public function appendContents(string $relativePath, $contents): bool|int
  {
    $fullPath = $this->resolvePath($relativePath);
    return file_put_contents($fullPath, $contents, FILE_APPEND);
  }

  private function core(): WP_Filesystem_Base
  {
    global $wp_filesystem;

    if (!($wp_filesystem instanceof WP_Filesystem_Base)) {
      require_once(ABSPATH . 'wp-admin/includes/plugin.php');
      require_once(ABSPATH . 'wp-admin/includes/file.php');
      WP_Filesystem();
    }

    return $wp_filesystem;
  }

  private function __construct() {}
}
