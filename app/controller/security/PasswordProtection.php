<?php

namespace Full\Customer\Security;

defined('ABSPATH') || exit;

class PasswordProtection
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('enablePasswordProtection')) :
      return;
    endif;

    $cls = new self($env);

    add_action('init', [$cls, 'disableCache'], 1);
    add_action('init', [$cls, 'processLogin']);
    add_action('template_include', [$cls, 'blockAccess'], PHP_INT_MAX);
  }

  public function disableCache(): void
  {
    if (!defined('DONOTCACHEPAGE')) {
      define('DONOTCACHEPAGE', true);
    }
  }

  public function processLogin(): void
  {
    $password = filter_input(INPUT_POST, 'password');

    if (!$password || isset($_COOKIE['fc-login'])) :
      return;
    endif;

    if ($password !== $this->env->get('sitePassword')) :
      wp_safe_redirect(home_url() . '?loginError');
      exit;
    endif;

    setcookie(
      'fc-login',
      1,
      time() + HOUR_IN_SECONDS
    );
    $_COOKIE['fc-login'] = 1;
  }

  public function blockAccess(string $template): string
  {
    if (!isset($_COOKIE['fc-login'])) :
      $themeFile = get_stylesheet_directory() . '/full-customer/password-protected.php';
      $pluginFile = FULL_CUSTOMER_APP . '/views/password-protected.php';

      $template = file_exists($themeFile) ? $themeFile : $pluginFile;
    endif;

    return $template;
  }
}

PasswordProtection::attach();
