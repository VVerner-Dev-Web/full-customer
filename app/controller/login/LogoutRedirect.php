<?php

namespace Full\Customer\Login;

defined('ABSPATH') || exit;

class LogoutRedirect
{
  public $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('redirectAfterLogout')) :
      return;
    endif;

    $cls = new self($env);
    add_filter('wp_logout', [$cls, 'redirect'], 5);
  }

  public function redirect()
  {
    wp_safe_redirect(home_url($this->env->get('redirectAfterLogout')));
    exit;
  }
}

LogoutRedirect::attach();
