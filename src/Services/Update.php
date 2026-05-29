<?php

namespace FC\Services;

use FC\FileSystem;
use FC\PluginRepository;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class Update
{
  public function __construct()
  {
    add_action('plugins_loaded', [$this, 'initiate']);
  }

  public function initiate(): void
  {
    $repo = new PluginRepository();
    $fs = FileSystem::instance();

    foreach ($repo->getPlugins() as $plugin) {
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
