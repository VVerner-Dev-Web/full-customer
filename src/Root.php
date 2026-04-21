<?php

namespace FC;

use FC\Services\Analytics;
use FC\Services\Connection;
use FC\Services\Fragments;
use FC\WordPress\Admin;
use FC\WordPress\Http;

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
    new Http;

    // SERVICES;
    new Analytics;
    new Connection;
    new Fragments;
  }
}
