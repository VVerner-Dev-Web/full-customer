<?php

namespace Full\Customer\Speed;

defined('ABSPATH') || exit;

class Revisions
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('disablePostRevisions')) :
      return;
    endif;

    add_filter('wp_revisions_to_keep', '__return_false', PHP_INT_MAX);
  }
}

Revisions::attach();
