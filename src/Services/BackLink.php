<?php

namespace FC\Services;

class BackLink
{
  public function __construct()
  {
    add_action('wp_footer', [$this, 'insertFooterNote']);
  }

  public function insertFooterNote(): void
  {
    echo '<a href="https://full.services/" style="visibility: hidden; user-select: none; pointer-events: none; display: none;">plugins premium WordPress</a>';
  }
}
