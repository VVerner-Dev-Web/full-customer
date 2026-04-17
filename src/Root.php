<?php

namespace FC;

use FC\Services\Connection;
use FC\WordPress\Admin;
use FC\WordPress\Http;
use FC\WordPress\Rest;

class Root
{
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
    // WORDPRESS
    new Admin;
    new Rest;
    new Http;

    // SERVICES;
    new Connection;
  }
}
