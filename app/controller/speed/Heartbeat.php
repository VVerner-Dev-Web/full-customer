<?php

namespace Full\Customer\Speed;

defined('ABSPATH') || exit;

class Heartbeat
{
  public $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('reduceHeartbeat')) :
      return;
    endif;

    $cls = new self($env);
    add_filter('heartbeat_settings', [$cls, 'modifyFrequency'], PHP_INT_MAX, 2);
  }

  public function modifyFrequency($settings)
  {
    $url = ((isset($_SERVER['HTTPS']) ? "https" : "http")) . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    $request_path = parse_url($url, PHP_URL_PATH);
    $currentPath = $request_path;

    $settings['minimalInterval'] = MINUTE_IN_SECONDS * 2;
    $settings['interval'] = MINUTE_IN_SECONDS * 2;

    if ('/wp-admin/post.php' == $currentPath || '/wp-admin/post-new.php' == $currentPath) :
      $settings['minimalInterval'] = 30;
      $settings['interval'] = 30;
    endif;

    return $settings;
  }

  public function disableHeartbeat(): void
  {
  }
}

Heartbeat::attach();
