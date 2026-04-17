<?php

namespace FC;

use FC\WordPress\AdminPages;
use FC\WordPress\Rest;

class Root
{
  private array $classes = [];

  public function init(): void
  {
    $this->files();
    $this->classes();
  }

  private function files(): void
  {
    require_once FileSystem::instance()->resolvePath('src/helpers.php');
  }

  private function classes(): void
  {
    new AdminPages;
    new Rest;
  }
}
