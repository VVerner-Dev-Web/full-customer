<?php

namespace Full\Customer\Login;

defined('ABSPATH') || exit;

class Url
{
  public Settings $env;
  public $loginSlug;

  private function __construct(Settings $env)
  {
    $this->env = $env;
    $this->loginSlug = $this->env->get('changedLoginUrl');
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('enableChangeLoginUrl')) :
      return;
    endif;

    $cls = new self($env);

    add_action('init', [$cls, 'redirectOnCustomLoginUrl']);
    add_filter('lostpassword_url', [$cls, 'updateLostPasswordUrl']);
    add_action('login_head', [$cls, 'redirectOnDefaultLoginUrls']);
    add_action('wp_login_failed', [$cls, 'redirectOnFail']);
    add_action('wp_logout', [$cls, 'redirectOnSuccessfulLogout']);
  }

  public function redirectOnCustomLoginUrl(): void
  {
    $inputUrl = sanitize_text_field(filter_input(INPUT_SERVER, 'REQUEST_URI'));

    if (false !== strpos($inputUrl, 'interim-login=1')) {
      remove_action('login_head', [$this, 'redirectOnDefaultLoginUrls']);
    }

    if (false !== strpos($inputUrl, 'checkemail=confirm') || false !== strpos($inputUrl, 'action=rp') || false !== strpos($inputUrl, 'action=resetpass')) {
      remove_action('login_head', [$this, 'redirectOnDefaultLoginUrls']);
    }

    if (false !== strpos($inputUrl, $this->loginSlug) && substr($inputUrl, -1) !== '/') {
      $inputUrl .= '/';
    }

    if (false !== strpos($inputUrl, '/' . $this->loginSlug . '/')) {
      wp_safe_redirect(home_url('wp-login.php?' . $this->loginSlug . '&redirect=false'));
      exit;
    }
  }

  public function updateLostPasswordUrl(string $lostpassword_url): string
  {
    return $lostpassword_url . '&' . $this->loginSlug;
  }

  public function redirectOnDefaultLoginUrls(): void
  {
    global  $interim_login;
    $inputUrl = sanitize_text_field(filter_input(INPUT_SERVER, 'REQUEST_URI'));

    if (!is_user_logged_in() && false !== strpos($inputUrl, 'wp-login.php') && false === strpos($inputUrl, $this->loginSlug) && 'success' != $interim_login) {
      wp_safe_redirect(home_url('404'), 302);
      exit;
    }
  }

  public function redirectOnFail(): void
  {
    wp_safe_redirect(home_url('wp-login.php?' . $this->loginSlug . '&redirect=false&failed_login=true'));
    exit;
  }

  public function redirectOnSuccessfulLogout(): void
  {
    wp_safe_redirect(home_url('wp-login.php?' . $this->loginSlug . '&redirect=false'));
    exit;
  }
}

Url::attach();
