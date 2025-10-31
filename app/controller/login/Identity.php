<?php

namespace Full\Customer\Login;

defined('ABSPATH') || exit;

class Identity
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

    if (!$env->get('useSiteIdentity')) :
      return;
    endif;

    $cls = new self($env);

    add_filter('login_headerurl', 'get_site_url');
    add_action('login_head', [$cls, 'loginIconUrl']);
    add_filter('login_title', [$cls, 'loginTitle']);
  }

  public function loginIconUrl(): void
  {
    if (!has_site_icon()) :
      return;
    endif;

    echo "<style>.login h1 a {background-image: url('" . get_site_icon_url(180) . "') !important}</style>";
  }

  public function loginTitle(): string
  {
    return 'Entrar no site ' . get_bloginfo('name');
  }
}

Identity::attach();
