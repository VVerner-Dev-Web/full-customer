<?php

namespace FC\Services;

class LocalLicenseProcessor
{
  private array $map;

  public function __construct()
  {
    $this->map = [
      'wp-rocket' => '__return_true',
      'advanced-custom-fields-pro' => [$this, 'acfPRO'],
      'all-in-one-wp-security-and-firewall-premium' => [$this, 'aiowpspf'],
      'wp-optimize-premium' => [$this, 'wpOptimize'],
      'wpforms' => [$this, 'wpforms'],
      'perfmatters' => [$this, 'perfmatters'],
      'happy-elementor-addons-pro' => [$this, 'happyElementorAddons'], // todo
      // 'updraftplus' => [$this, 'updraftplus'], // corrigir plugin no repositório para pro
    ];
  }

  public function isAvailableForPlugin(string $plugin): bool
  {
    error_log($plugin);
    return isset($this->map[$plugin]);
  }

  public function process(string $plugin, string $license): bool
  {
    return call_user_func($this->map[$plugin], $license);
  }

  public function perfmatters(string $license): bool
  {
    if (!class_exists('\Perfmatters\License', false)) {
      return false;
    }

    update_option('perfmatters_edd_license_key', $license, false);

    return \Perfmatters\License::activate();
  }

  public function happyElementorAddons(string $license): bool
  {
    if (!function_exists('hapro_get_appsero')) {
      return false;
    }

    hapro_get_appsero()->license()->license_form_submit([
      'license_key' => $license,
      'submit' => 1,
      '_action' => 'active',
      '_nonce' => wp_create_nonce('Happy Elementor Addons Pro')
    ]);

    $license = hapro_get_appsero()->license()->get_license();

    return isset($license['status']) && $license['status'] === 'active';
  }

  public function acfPRO(string $license): bool
  {
    if (!function_exists('acf_pro_activate_license')) {
      return false;
    }

    $done = acf_pro_activate_license($license, true);

    return is_array($done) && (int) $done['success'] === 1;
  }

  public function wpforms(string $license): bool
  {
    if (!function_exists('wpforms')) {
      return false;
    }

    if (!class_exists('\WPForms_License', false)) {
      $file = WP_CONTENT_DIR . '/plugins/wpforms/pro/includes/admin/class-license.php';

      if (!file_exists($file)) {
        return false;
      }

      require_once($file);
    }

    $done = (new \WPForms_License())->verify_key($license, false);

    return $done ? true : false;
  }

  public function aiowpspf(string $license): bool
  {
    return $this->updraftFamily('all-in-one-wp-security-and-firewall-premium', $license);
  }

  public function updraftplus(string $license): bool
  {
    return $this->updraftFamily('all-in-one-wp-security-and-firewall-premium', $license);
  }

  public function wpOptimize(string $license): bool
  {
    return $this->updraftFamily('wp-optimize-premium', $license);
  }

  private function updraftFamily(string $plugin, string $license): bool
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
        'userid' => 2,
        'slug' => $plugin,
        'email' => $email,
        'password' => $password
      ]
    ]);

    $response = json_decode(wp_remote_retrieve_body($request), true);

    return is_array($response) && isset($response['code']) && $response['code'] === 'OK';
  }
}
