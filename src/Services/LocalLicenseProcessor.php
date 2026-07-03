<?php

namespace FC\Services;

use WP_Http_Cookie;

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
      'jet-theme-core' => [$this, 'jetThemeCore'],
      'elementor-pro' => [$this, 'elementorPro'],
    ];
  }

  public function process(string $plugin, string $license): array
  {
    if (!isset($this->map[$plugin])) {
      return [
        'success' => false,
      ];
    }

    return call_user_func($this->map[$plugin], $license);
  }

  public function elementorPro(string $license): array
  {
    [$license, $elementorCookies] = explode(':', $license);
    $elementorCookies = json_decode(base64_decode($elementorCookies), true);

    $elementorCookies = array_map(fn($c) => new WP_Http_Cookie($c), $elementorCookies);

    $url = rest_url('elementor-one/v1/connect/authorize?clearSession=true');

    $request = wp_remote_post($url, [
      'sslverify' => false,
      'timeout' => 15,
      'cookies' => $_COOKIE,
      'headers' => [
        'X-WP-Nonce' => wp_create_nonce('wp_rest'),
      ],
    ]);

    if (is_wp_error($request)) {
      return [
        'success' => false,
        'trace' => 'local_authorize',
      ];
    }

    $response = json_decode(wp_remote_retrieve_body($request), true);
    $oauthUrl = $response['data'] ?? null;

    if (!$oauthUrl) {
      return [
        'success' => false,
        'trace' => 'elementor-one/v1/connect/authorize?clearSession=true',
      ];
    }

    $request = wp_remote_get($oauthUrl, [
      'cookies' => $elementorCookies,
    ]);

    $consentUrl = $request['http_response']->get_response_object()->url;

    $parsed = wp_parse_url($consentUrl);
    $queryParams = [];
    wp_parse_str($parsed['query'], $queryParams);
    $challenge = $queryParams['consent_challenge'] ?? null;

    if (!$challenge) {
      return [
        'success' => false,
        'trace' => 'consentUrl',
      ];
    }

    $allCookies = $elementorCookies;

    foreach ($request['http_response']->get_response_object()->cookies as $cookie) {
      $allCookies[] = new WP_Http_Cookie([
        'name' => $cookie->name,
        'value' => $cookie->value,
        'path' => $cookie->path ?? '/',
        'domain' => $cookie->domain ?? 'my.elementor.com',
      ]);
    }

    $response = wp_remote_post('https://my.elementor.com/connect/api/v1/consent', [
      'cookies' => $allCookies,
      'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Referer' => $consentUrl,
      ],
      'body' => wp_json_encode([
        'consentChallenge' => $challenge,
        'subscriptionId' => $license,
        'grantScope' => ['openid', 'offline_access', 'share_usage_data'],
      ]),
      'timeout' => 15,
    ]);

    $respBody = json_decode(wp_remote_retrieve_body($response), true);
    $redirectUrl = $respBody['redirectUrl'] ?? null;

    if (!$redirectUrl) {
      return [
        'success' => false,
        'trace' => 'subscription_select',
      ];
    }

    $done = wp_remote_get($redirectUrl, [
      'cookies' => $allCookies,
      'sslverify' => false,
      'timeout' => 15,
    ]);

    $localUrl = $done['http_response']->get_response_object()->url;

    $qs = [];
    wp_parse_str(wp_parse_url($localUrl, PHP_URL_QUERY), $qs);

    if (!$qs['redirect_to']) {
      return [
        'success' => false,
        'trace' => 'redirect_to',
      ];
    }

    return [
      'success' => true,
      'redirectUrl' => $qs['redirect_to'] ?? '',
    ];
  }

  public function jetThemeCore(string $license): array
  {
    $cookies = [];
    foreach ($_COOKIE as $name => $value) {
      $cookies[] = new \WP_Http_Cookie([
        'name' => $name,
        'value' => $value
      ]);
    }

    $request = wp_remote_post(admin_url('admin-ajax.php'), [
      'sslverify' => false,
      'cookies' => $cookies,
      'body' => [
        'action' => 'jet_license_action',
        'data' => [
          'license' => $license,
          'action' => 'activate',
          'nonce' => wp_create_nonce('jet-dashboard')
        ]
      ],
    ]);

    if (is_wp_error($request)) {
      return ['success' => false];
    }

    $response = json_decode(wp_remote_retrieve_body($request), true);

    return ['success' => is_array($response) && isset($response['status']) && $response['status'] === 'success'];
  }

  public function essentialAddons(string $license): array
  {
    global $wpdb;

    if (!defined('EAEL_PRO_PLUGIN_FILE')) {
      return ['success' => false];
    }

    $transientKey = 'fc/local-license-processor/essential-addons';
    delete_transient($transientKey);

    $m = \Essential_Addons_Elementor\Pro\Classes\License\Manager::get_instance([
      'plugin_file' => EAEL_PRO_PLUGIN_FILE,
      'version' => EAEL_PRO_PLUGIN_VERSION,
      'item_id' => EAEL_SL_ITEM_ID,
      'item_name' => EAEL_SL_ITEM_NAME,
      'item_slug' => EAEL_SL_ITEM_SLUG,
      'textdomain' => 'essential-addons-elementor',
      'db_prefix' => EAEL_SL_ITEM_SLUG,
      'page_slug' => 'eael-settings',
      'scripts_handle' => 'eael-admin-dashboard',
      'screen_id' => ["toplevel_page_eael-settings"],
      'api' => 'ajax',
      'ajax' => [
        'textdomain' => 'essential-addons-elementor',
        'action_prefix' => 'essential-addons-elementor'
      ],
      'migrate_from' => [
        'license' => 'essential-addons-elementor-license-key',
        'status' => 'essential-addons-elementor-license-status'
      ]
    ]);

    $response = $m->activate(['license_key' => $license]);

    if (is_wp_error($response)) {
      return ['success' => false];
    }

    if (!isset($response->license) || 'required_otp' !== $response->license) {
      return ['success' => false];
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
      return ['success' => false];
    }

    $done = $m->submit_otp([
      'license_key' => $license,
      'otp' => $otp
    ]);

    if (is_wp_error($done)) {
      return ['success' => false];
    }

    return ['success' => isset($done->license) && $done->license === 'valid'];
  }

  public function rankMath(string $license): array
  {
    if (!function_exists('rank_math_pro')) {
      return ['success' => false];
    }

    [$user, $licenseKey] = explode(':', $license);

    update_option(
      'rank_math_reseller_data',
      [
        'username' => $user,
        'api_key' => $licenseKey,
      ],
      false
    );

    \RankMathPro\Admin\Licence_Activation::activate_licence();

    $data = \RankMath\Admin\Admin_Helper::get_registration_data();

    return ['success' => is_array($data) && $data['connected']];
  }

  public function ultimateAddons(string $license): array
  {
    return $this->bsfFamily('ultimate-elementor', 'uael', $license);
  }

  public function astra(string $license): array
  {
    return $this->bsfFamily('astra-addon', 'astra-addon', $license);
  }

  public function seoPress(string $license): array
  {
    $file = WP_PLUGIN_DIR . '/wp-seopress-pro/inc/admin/callbacks/License.php';

    if (!file_exists($file)) {
      return ['success' => false];
    }

    if (!function_exists('seopress_automatic_license_activation')) {
      include_once($file);
    }

    if (defined('SEOPRESS_LICENSE_KEY')) {
      return ['success' => false];
    }

    delete_option('seopress_pro_license_automatic_attempt');
    define('SEOPRESS_LICENSE_KEY', $license);

    seopress_automatic_license_activation();

    $done = get_option('seopress_pro_license_status') === 'valid';

    if ($done) {
      update_option('seopress_pro_license_key', $license, false);
    }

    return ['success' => $done];
  }

  public function perfmatters(string $license): array
  {
    if (!class_exists('\Perfmatters\License', false)) {
      return ['success' => false];
    }

    update_option('perfmatters_edd_license_key', $license, false);

    return ['success' => \Perfmatters\License::activate()];
  }

  public function happyElementorAddons(string $license): array
  {
    if (!function_exists('hapro_get_appsero')) {
      return ['success' => false];
    }

    hapro_get_appsero()->license()->license_form_submit([
      'license_key' => $license,
      'submit' => 1,
      '_action' => 'active',
      '_nonce' => wp_create_nonce('Happy Elementor Addons Pro')
    ]);

    $license = hapro_get_appsero()->license()->get_license();

    return ['success' => isset($license['status']) && $license['status'] === 'active'];
  }

  public function acfPRO(string $license): array
  {
    if (!function_exists('acf_pro_activate_license')) {
      return ['success' => false];
    }

    $done = acf_pro_activate_license($license, true);

    return ['success' => is_array($done) && (int) $done['success'] === 1];
  }

  public function wpforms(string $license): array
  {
    if (!function_exists('wpforms')) {
      return ['success' => false];
    }

    if (!class_exists('\WPForms_License', false)) {
      $file = WP_CONTENT_DIR . '/plugins/wpforms/pro/includes/admin/class-license.php';

      if (!file_exists($file)) {
        return ['success' => false];
      }

      require_once($file);
    }

    $done = (new \WPForms_License())->verify_key($license, false);

    return ['success' => $done ? true : false];
  }

  public function aiowpspf(string $license): array
  {
    return $this->updraftFamily('all-in-one-wp-security-and-firewall-premium', $license);
  }

  public function updraftplus(string $license): array
  {
    return $this->updraftFamily('updraftplus', $license);
  }

  public function wpOptimize(string $license): array
  {
    return $this->updraftFamily('wp-optimize-premium', $license);
  }

  private function updraftFamily(string $plugin, string $license): array
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

    return ['success' => is_array($response) && isset($response['code']) && $response['code'] === 'OK'];
  }

  public function bsfFamily(string $plugin, string $productId, string $license): array
  {
    $file = WP_PLUGIN_DIR . '/' . $plugin . '/admin/bsf-core/index.php';

    if (!file_exists($file)) {
      return ['success' => false];
    }

    if (!class_exists('BSF_License_Manager', false)) {
      include_once($file);
    }

    $result = \BSF_License_Manager::instance()->bsf_process_license_activation([
      'privacy_consent' => true,
      'terms_conditions_consent' => true,
      'product_id' => $productId,
      'license_key' => $license,
    ]);

    return ['success' => is_array($result) && isset($result['success']) && $result['success']];
  }
}
