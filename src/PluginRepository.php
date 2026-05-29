<?php

namespace FC;

use FC\FileSystem;

class PluginRepository
{
  private string $repositoryFilename;

  public function __construct()
  {
    $this->repositoryFilename = trailingslashit(WP_CONTENT_DIR) . 'full-plugin-repository.json';
  }

  public function getPlugins(bool $force = false): array
  {
    $fs = FileSystem::instance();

    $lastUpdated = (int) get_option('fc/plugin-repository/updated-at', time());
    $now = (int) time();

    $hasExpired = $now - $lastUpdated > DAY_IN_SECONDS;

    if (
      $force
      || $hasExpired
      || !$fs->isFile($this->repositoryFilename)
    ) {
      $this->updateRepository();
    }

    $plugins = [];

    if ($fs->isFile($this->repositoryFilename)) {
      $plugins = json_decode($fs->getContents($this->repositoryFilename), true);
    }

    return $plugins ?? [];
  }

  private function updateRepository(): void
  {
    $data = fcDashboardAPI('GET', 'plugin-repository/all');
    $plugins = $data['success'] ? $data['data'] : [];

    FileSystem::instance()->putContents(
      $this->repositoryFilename,
      wp_json_encode($plugins)
    );

    update_option('fc/plugin-repository/updated-at', time());
  }
}
