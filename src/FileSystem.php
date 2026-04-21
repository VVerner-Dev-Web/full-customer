<?php

namespace FC;

use WP_Filesystem_Base;

final class FileSystem
{
  private static $instance = null;
  private string $baseUrl;

  public static function instance(): self
  {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function resolvePath(string $path): string
  {
    $path = rtrim(FULL_CUSTOMER_PATH, '/') . '/' . ltrim($path, '/');
    $path = str_replace(
      ['/', '\\'],
      [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR],
      $path
    );

    return $path;
  }

  public function getUrl(string $relative_path): string
  {
    if (!isset($this->baseUrl)) {
      $this->baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE));
    }

    return $this->baseUrl . ltrim($relative_path, '/');
  }

  public function getContents(string $relative_path)
  {
    $full_path = $this->resolvePath($relative_path);
    return $this->core()->get_contents($full_path);
  }

  public function delete(string $relative_path, bool $recursive = false)
  {
    $full_path = $this->resolvePath($relative_path);
    return $this->core()->delete($full_path, $recursive);
  }

  public function putContents(string $relative_path, $contents, $mode = false)
  {
    $full_path = $this->resolvePath($relative_path);
    return $this->core()->put_contents($full_path, $contents, $mode);
  }

  public function isFile(string $relative_path): bool
  {
    $full_path = $this->resolvePath($relative_path);
    return $this->core()->is_file($full_path);
  }

  public function isDir(string $relative_path): bool
  {
    $full_path = $this->resolvePath($relative_path);
    return $this->core()->is_dir($full_path);
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
