<?php

namespace Full\Customer\Security;

defined('ABSPATH') || exit;

class UserSwitch
{
  public $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('')) :

    endif;

    $cls = new self($env);
  }
}

UserSwitch::attach();
