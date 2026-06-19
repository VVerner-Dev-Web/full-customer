<?php

namespace FC\Services;

class LocalLicenseProcessor
{
  private array $map;

  public function __construct()
  {
    $this->map = [
      'advanced-custom-fields-pro' => [$this, 'acfPRO'],
      'all-in-one-wp-security-and-firewall-premium' => [$this, 'aiowpspf'],
    ];
  }

  public function isAvailableForPlugin(string $plugin): bool
  {
    return isset($this->map[$plugin]);
  }

  public function process(string $plugin, string $license): bool
  {
    return call_user_func($this->map[$plugin], $license);
  }

  public function acfPRO(string $license): bool
  {
    if (!function_exists('acf_pro_activate_license')) {
      return false;
    }

    $done = acf_pro_activate_license($license, true);

    return is_array($done) && (int) $done['success'] === 1;
  }

  public function aiowpspf(string $license): bool
  {
    [$email, $password] = explode(':', $license);

    $request = wp_remote_post(admin_url('admin-ajax.php'), [
      'cookies' => $_COOKIE,
      'headers' => [
        'Content-Type' => 'application/x-www-form-urlencoded'
      ],
      'sslverify' => false,
      'body' => [
        'action' => 'udmupdater_ajax',
        'subaction' => 'connect',
        'nonce' => wp_create_nonce('udmupdater-ajax-nonce'),
        'userid' => 2, // aiowps-premium-core.php:L552
        'slug' => 'all-in-one-wp-security-and-firewall-premium',
        'email' => $email,
        'password' => $password
      ]
    ]);

    $response = json_decode(wp_remote_retrieve_body($request), true);

    return is_array($response) && isset($response['code']) && $response['code'] === 'OK';
  }
}
