<?php

namespace Full\Customer\Login;

defined('ABSPATH') || exit;

class LoginRedirect
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('redirectAfterLogin')) :
      return;
    endif;

    $cls = new self($env);
    add_filter('login_redirect', [$cls, 'redirect'], PHP_INT_MAX);
  }

  public function redirect(): string
  {
    return home_url($this->env->get('redirectAfterLogin'));
  }
}

LoginRedirect::attach();
