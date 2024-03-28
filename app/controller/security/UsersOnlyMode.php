<?php

namespace Full\Customer\Security;

defined('ABSPATH') || exit;

class UsersOnlyMode
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('enableUsersOnlyMethod')) :
      return;
    endif;

    $cls = new self($env);

    add_action('template_redirect', [$cls, 'redirectToLogin'], 0);
  }

  public function redirectToLogin(): void
  {
    if (!is_user_logged_in()) :
      wp_safe_redirect(wp_login_url(home_url()));
      exit;
    endif;
  }
}

UsersOnlyMode::attach();
