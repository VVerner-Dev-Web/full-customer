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
