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
      'happy-elementor-addons-pro' => [$this, 'happyElementorAddons'],
      'updraftplus' => [$this, 'updraftplus'],
      'wp-seopress-pro' => [$this, 'seoPress'],
      'ultimate-elementor' => [$this, 'ultimateAddons'],
      'astra-addon' => [$this, 'astra'],
      'seo-by-rank-math-pro' => [$this, 'rankMath'],
      'essential-addons-elementor' => [$this, 'essentialAddons'],
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

  public function essentialAddons(string $license): bool
  {
    global $wpdb;

    if (!defined('EAEL_PRO_PLUGIN_FILE')) {
      return false;
    }

    $transientKey = 'fc/local-license-processor/essential-addons';
    delete_transient($transientKey);

    $m = \Essential_Addons_Elementor\Pro\Classes\License\Manager::get_instance([
      'plugin_file'    => EAEL_PRO_PLUGIN_FILE,
      'version'        => EAEL_PRO_PLUGIN_VERSION,
      'item_id'        => EAEL_SL_ITEM_ID,
      'item_name'      => EAEL_SL_ITEM_NAME,
      'item_slug'      => EAEL_SL_ITEM_SLUG,
      'textdomain'     => 'essential-addons-elementor',
      'db_prefix'      => EAEL_SL_ITEM_SLUG,
      'page_slug'      => 'eael-settings',
      'scripts_handle' => 'eael-admin-dashboard',
      'screen_id'      => ["toplevel_page_eael-settings"],
      'api'            => 'ajax',
      'ajax'           => [
        'textdomain'    => 'essential-addons-elementor',
        'action_prefix' => 'essential-addons-elementor'
      ],
      'migrate_from' => [
        'license' => 'essential-addons-elementor-license-key',
        'status' => 'essential-addons-elementor-license-status'
      ]
    ]);

    $response = $m->activate(['license_key' => $license]);

    if (is_wp_error($response)) {
      return false;
    }

    if (!isset($response->license) || 'required_otp' !== $response->license) {
      return false;
    }

    $otp = null;

    // FYI: 120s
    for ($i = 0; $i < 24; $i++) {
      $data = $wpdb->get_var("SELECT `option_value` FROM `{$wpdb->options}` WHERE `option_name` = '_transient_{$transientKey}';");
      $data = json_decode($data, true);

      if (is_array($data) && isset($data['otp'])) {
        $otp = $data['otp'];
        break;
      }

      sleep(5);
    }

    if (!$otp) {
      return false;
    }

    $done = $m->submit_otp([
      'license_key' => $license,
      'otp' => $otp
    ]);

    if (is_wp_error($done)) {
      return false;
    }

    return isset($done->license) && $done->license === 'valid';
  }

  public function rankMath(string $license): bool
  {
    if (!function_exists('rank_math_pro')) {
      return false;
    }

    [$user, $licenseKey] = explode(':', $license);

    update_option(
      'rank_math_reseller_data',
      [
        'username' => $user,
        'api_key'  => $licenseKey,
      ],
      false
    );

    \RankMathPro\Admin\Licence_Activation::activate_licence();

    $data = \RankMath\Admin\Admin_Helper::get_registration_data();

    return is_array($data) && $data['connected'];
  }

  public function ultimateAddons(string $license): bool
  {
    return $this->bsfFamily('ultimate-elementor', 'uael', $license);
  }

  public function astra(string $license): bool
  {
    return $this->bsfFamily('astra-addon', 'astra-addon', $license);
  }

  public function seoPress(string $license): bool
  {
    $file = WP_PLUGIN_DIR . '/wp-seopress-pro/inc/admin/callbacks/License.php';

    if (!file_exists($file)) {
      return false;
    }

    if (!function_exists('seopress_automatic_license_activation')) {
      include_once($file);
    }

    if (defined('SEOPRESS_LICENSE_KEY')) {
      return false;
    }

    delete_option('seopress_pro_license_automatic_attempt');
    define('SEOPRESS_LICENSE_KEY', $license);

    seopress_automatic_license_activation();

    $done = get_option('seopress_pro_license_status') === 'valid';

    if ($done) {
      update_option('seopress_pro_license_key', $license, false);
    }

    return $done;
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
    return $this->updraftFamily('updraftplus', $license);
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

  public function bsfFamily(string $plugin, string $productId, string $license): bool
  {
    $file = WP_PLUGIN_DIR . '/' . $plugin . '/admin/bsf-core/index.php';

    if (!file_exists($file)) {
      return false;
    }

    if (!class_exists('BSF_License_Manager', false)) {
      include_once($file);
    }

    $result = \BSF_License_Manager::instance()->bsf_process_license_activation([
      'privacy_consent'          => true,
      'terms_conditions_consent' => true,
      'product_id'               => $productId,
      'license_key'              => $license,
    ]);

    return is_array($result) && isset($result['success']) && $result['success'];
  }
}
