<?php

namespace FC\Services;

use FC\FileSystem;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class Update
{
  public function __construct()
  {
    add_action('plugins_loaded', [$this, 'initiate']);
  }

  public function initiate(): void
  {
    $data = fcDashboardAPI('GET', 'plugin-repository/updates');
    $plugins = $data['success'] ? $data['data'] : [];

    $fs = FileSystem::instance();

    foreach ($plugins as $plugin) {
      $path = trailingslashit(WP_PLUGIN_DIR) . $plugin['plugin'];

      if ($fs->isFile($path)) {
        PucFactory::buildUpdateChecker(
          $plugin['puc'],
          $path,
          $plugin['slug']
        );
      }
    }
  }
}
