<?php

namespace FC\Services;

use FC\FileSystem;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class Update
{
  private string $filename = 'fc-updates.json';
  private ?string $cacheFile = null;

  public const TIMEOUT = 12 * HOUR_IN_SECONDS;

  public function __construct()
  {
    $fs = FileSystem::instance();
    $path = $fs->wpContentDir() . $this->filename;

    $this->cacheFile = $fs->resolvePath($path);

    add_action('plugins_loaded', [$this, 'initiate'], 0);
    add_action('fc/updates/invalidate', [$this, 'invalidate']);
  }

  public function invalidate(): void
  {
    $fs = FileSystem::instance();
    if ($fs->isFile($this->cacheFile)) {
      $fs->delete($this->cacheFile);
    }
  }

  public function initiate(): void
  {
    $fs = FileSystem::instance();

    foreach ($this->getUpdates() as $update) {
      if (!empty($update['path']) && $fs->isFile($update['path'])) {
        remove_all_filters('puc_is_slug_in_use-' . $update['slug']);
        PucFactory::buildUpdateChecker($update['puc'], $update['path'], $update['slug']);
      }
    }
  }

  private function getUpdates(): array
  {
    $fs = FileSystem::instance();

    if ($fs->isFile($this->cacheFile) && $fs->modifiedTime($this->cacheFile) > time() - self::TIMEOUT) {
      $plugins = json_decode($fs->getContents($this->cacheFile), true);
      $plugins = is_array($plugins) ? $plugins : [];
      return $plugins;
    }

    $data = fcDashboardAPI('GET', 'plugin-repository/updates');
    $plugins = $data['success'] ? $data['data'] : [];

    $updates = [];

    foreach ($plugins as $plugin) {
      $updates[] = [
        'slug' => $plugin['slug'],
        'puc' => $plugin['puc'],
        'path' => trailingslashit(WP_PLUGIN_DIR) . $plugin['plugin'],
      ];
    }

    $fs->putContents($this->cacheFile, wp_json_encode($updates));

    return $updates;
  }
}
