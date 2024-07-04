<?php

namespace Full\Customer;

defined('ABSPATH') || exit;

class License
{
  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_action('wp', [$cls, 'enqueueCronJob']);
    add_action('full-customer/license-check', [$cls, 'updateStatus']);
  }

  public function enqueueCronJob(): void
  {
    if (!wp_next_scheduled('full-customer/license-check')) :
      wp_schedule_event(current_time('timestamp'), 'daily', 'full-customer/license-check');
    endif;
  }

  public static function updateStatus(): bool
  {
    $url   = fullCustomer()->getFullDashboardApiUrl() . '-customer/v1/license';

    $response = wp_remote_post($url, [
      'sslverify' => false,
      'headers'   => ['Content-Type' => 'application/json'],
      'body'      => json_encode([
        'site_url'  => home_url(),
      ])
    ]);

    if (is_wp_error($response)) :
      return false;
    endif;

    $response = wp_remote_retrieve_body($response);
    $response = json_decode($response, true);

    if (!$response || !isset($response['status'])) :
      return false;
    endif;

    update_option('full/license-status', $response, false);

    return true;
  }

  public static function status(): array
  {
    return (array) get_option('full/license-status', [
      'expireDate' => null,
      'status'  => 'new',
      'active'  => false,
      'plan'    => ''
    ]);
  }

  public static function isActive(): bool
  {
    return self::status()['active'];
  }
}

License::attach();
