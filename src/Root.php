<?php

namespace FC;

use FC\Services\AdminPage;
use FC\Services\Analytics;
use FC\Services\BackLink;
use FC\Services\Connection;
use FC\Services\MagicLink;
use FC\Services\Rest;
use FC\Services\Staff;
use FC\Services\Update;

class Root
{
  public function init(): void
  {
    $this->files();
    $this->classes();
  }

  private function files(): void
  {
    FileSystem::instance()->include('src/helpers.php');
  }

  private function classes(): void
  {
    new AdminPage;
    new Analytics;
    new Connection;
    new BackLink;
    new Staff;
    new MagicLink;
    new Update;
    new Rest;
  }
}
