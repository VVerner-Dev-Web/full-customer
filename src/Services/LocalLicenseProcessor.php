<?php

namespace FC\Services;

use WP_Http_Cookie;

class LocalLicenseProcessor
{
  private array $map;

  public function __construct()
  {
    $this->map = [
      'wp-rocket' => [$this, 'wpRocket'],
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
      'full-templates' => [$this, 'fullTemplates'],
    ];
  }

  public function process(string $plugin, string $license, string $step = '', array $state = []): array
  {
    if (!isset($this->map[$plugin])) {
      return [
        'success' => false,
      ];
    }

    return call_user_func($this->map[$plugin], $license, $step, $state);
  }

  public function fullTemplates(string $license): array
  {
    return [
      'success' => true,
    ];
  }

  public function wpRocket(string $license): array
  {
    return [
      'success' => true,
    ];
  }

  public function elementorPro(string $license, string $step = '', array $state = []): array
  {
    @set_time_limit(120);

    [$licenseKey, $elementorCookiesRaw] = explode(':', $license);
    $elementorCookies = json_decode(base64_decode($elementorCookiesRaw), true);

    if (empty($step)) {
      $oauthUrl = $this->getElementorOAuthUrl();
      if (!$oauthUrl) {
        return [
          'success' => false,
          'trace' => 'local_authorize_internal_error',
        ];
      }

      return [
        'success' => true,
        'completed' => false,
        'step' => 'fetch_consent',
        'message' => 'Autorização local obtida. Conectando à API do Elementor...',
        'state' => [
          'oauthUrl' => $oauthUrl,
        ]
      ];
    }

    if ($step === 'fetch_consent') {
      $oauthUrl = $state['oauthUrl'] ?? '';
      return $this->fetchElementorConsentUrl($oauthUrl, $elementorCookies);
    }

    if ($step === 'submit_consent') {
      $consentUrl = $state['consentUrl'] ?? '';
      $challenge = $state['challenge'] ?? '';
      $savedCookies = $state['cookies'] ?? [];
      return $this->submitElementorConsent($consentUrl, $challenge, $licenseKey, $savedCookies);
    }

    if ($step === 'finalize') {
      $redirectUrl = $state['redirectUrl'] ?? '';
      $savedCookies = $state['cookies'] ?? [];
      return $this->finalizeElementorActivation($redirectUrl, $savedCookies);
    }

    return [
      'success' => false,
      'trace' => 'invalid_step',
    ];
  }

  private function getElementorOAuthUrl(): ?string
  {
    $restRequest = new \WP_REST_Request('POST', '/elementor-one/v1/connect/authorize');
    $restRequest->set_param('clearSession', 'true');

    $response = rest_do_request($restRequest);
    if ($response->is_error()) {
      return null;
    }

    $responseData = $response->get_data();
    return $responseData['data'] ?? null;
  }

  private function fetchElementorConsentUrl(string $oauthUrl, array $elementorCookies): array
  {
    $cookies = array_map(fn($c) => new WP_Http_Cookie($c), $elementorCookies);

    $request = wp_remote_get($oauthUrl, [
      'cookies' => $cookies,
      'timeout' => 30,
    ]);

    if (is_wp_error($request)) {
      return [
        'success' => false,
        'trace' => 'consent_url_fetch_failed',
      ];
    }

    $consentUrl = $request['http_response']->get_response_object()->url;

    $parsed = wp_parse_url($consentUrl);
    $queryParams = [];
    wp_parse_str($parsed['query'] ?? '', $queryParams);
    $challenge = $queryParams['consent_challenge'] ?? null;

    if (!$challenge) {
      return [
        'success' => false,
        'trace' => 'consent_challenge_missing',
      ];
    }

    $allCookies = $elementorCookies;
    foreach ($request['http_response']->get_response_object()->cookies as $cookie) {
      $cookie = (array) $cookie;
      $allCookies[] = [
        'name' => $cookie['name'],
        'value' => $cookie['value'],
        'path' => isset($cookie['path']) && $cookie['path'] ? $cookie['path'] : '/',
        'domain' => isset($cookie['domain']) && $cookie['domain'] ? $cookie['domain'] : 'my.elementor.com',
      ];
    }

    return [
      'success' => true,
      'completed' => false,
      'step' => 'submit_consent',
      'message' => 'Conexão com a API do Elementor estabelecida. Autenticando...',
      'state' => [
        'consentUrl' => $consentUrl,
        'challenge' => $challenge,
        'cookies' => $allCookies,
      ]
    ];
  }

  private function submitElementorConsent(string $consentUrl, string $challenge, string $licenseKey, array $savedCookies): array
  {
    $cookies = array_map(fn($c) => new WP_Http_Cookie($c), $savedCookies);

    $response = wp_remote_post('https://my.elementor.com/connect/api/v1/consent', [
      'cookies' => $cookies,
      'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Referer' => $consentUrl,
      ],
      'body' => wp_json_encode([
        'consentChallenge' => $challenge,
        'subscriptionId' => $licenseKey,
        'grantScope' => ['openid', 'offline_access', 'share_usage_data'],
      ]),
      'timeout' => 30,
    ]);

    if (is_wp_error($response)) {
      return [
        'success' => false,
        'trace' => 'consent_submit_failed',
      ];
    }

    $respBody = json_decode(wp_remote_retrieve_body($response), true);
    $redirectUrl = $respBody['redirectUrl'] ?? null;

    if (!$redirectUrl) {
      return [
        'success' => false,
        'trace' => 'subscription_select',
      ];
    }

    $allCookies = $savedCookies;
    foreach ($response['http_response']->get_response_object()->cookies as $cookie) {
      $cookie = (array) $cookie;
      $allCookies[] = [
        'name' => $cookie['name'],
        'value' => $cookie['value'],
        'path' => isset($cookie['path']) && $cookie['path'] ? $cookie['path'] : '/',
        'domain' => isset($cookie['domain']) && $cookie['domain'] ? $cookie['domain'] : 'my.elementor.com',
      ];
    }

    return [
      'success' => true,
      'completed' => false,
      'step' => 'finalize',
      'message' => 'Autenticação com a FULL realizada. Finalizando ativação local...',
      'state' => [
        'redirectUrl' => $redirectUrl,
        'cookies' => $allCookies,
      ]
    ];
  }

  private function finalizeElementorActivation(string $redirectUrl, array $savedCookies): array
  {
    $cookies = array_map(fn($c) => new WP_Http_Cookie($c), $savedCookies);

    $done = wp_remote_get($redirectUrl, [
      'cookies' => $cookies,
      'sslverify' => false,
      'timeout' => 30,
    ]);

    if (is_wp_error($done)) {
      return [
        'success' => false,
        'trace' => 'callback_fetch_error',
      ];
    }

    $localUrl = $done['http_response']->get_response_object()->url;

    $qs = [];
    wp_parse_str(wp_parse_url($localUrl, PHP_URL_QUERY), $qs);

    if (empty($qs['redirect_to'])) {
      return [
        'success' => false,
        'trace' => 'redirect_to',
      ];
    }

    return [
      'success' => true,
      'completed' => true,
      'message' => 'Plugin ativado com sucesso e pronto para uso! Aproveite.',
      'redirectUrl' => $qs['redirect_to'],
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

  public function essentialAddons(string $license, string $step = '', array $state = []): array
  {
    if (!defined('EAEL_PRO_PLUGIN_FILE')) {
      return ['success' => false];
    }

    $transientKey = 'fc/local-license-processor/essential-addons';

    if (empty($step)) {
      return $this->startEssentialAddonsActivation($license, $transientKey);
    }

    if ($step === 'check_otp') {
      return $this->checkEssentialAddonsOTP($license, $transientKey, $state);
    }

    return ['success' => false];
  }

  private function getEssentialAddonsManager(): \Essential_Addons_Elementor\Pro\Classes\License\Manager
  {
    return \Essential_Addons_Elementor\Pro\Classes\License\Manager::get_instance([
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
  }

  private function startEssentialAddonsActivation(string $license, string $transientKey): array
  {
    delete_transient($transientKey);

    $m = $this->getEssentialAddonsManager();
    $response = $m->activate(['license_key' => $license]);

    if (is_wp_error($response)) {
      return ['success' => false];
    }

    if (!isset($response->license) || 'required_otp' !== $response->license) {
      return ['success' => false];
    }

    return [
      'success' => true,
      'completed' => false,
      'step' => 'check_otp',
      'message' => 'Chave enviada. Aguardando OTP ser gerado...',
      'state' => [
        'attempts' => 0
      ]
    ];
  }

  private function checkEssentialAddonsOTP(string $license, string $transientKey, array $state): array
  {
    global $wpdb;
    $attempts = (int) ($state['attempts'] ?? 0);

    sleep(5);

    $data = $wpdb->get_var("SELECT `option_value` FROM `{$wpdb->options}` WHERE `option_name` = '_transient_{$transientKey}';");
    $data = json_decode($data, true);

    $otp = null;
    if (is_array($data) && isset($data['otp'])) {
      $otp = $data['otp'];
    }

    if (!$otp) {
      if ($attempts >= 24) {
        return [
          'success' => false,
          'message' => 'Tempo esgotado aguardando o código OTP do Essential Addons.'
        ];
      }

      return [
        'success' => true,
        'completed' => false,
        'step' => 'check_otp',
        'message' => 'Aguardando o código OTP do Essential Addons (' . (($attempts + 1) * 5) . 's / 120s)...',
        'state' => [
          'attempts' => $attempts + 1
        ]
      ];
    }

    $m = $this->getEssentialAddonsManager();
    $done = $m->submit_otp([
      'license_key' => $license,
      'otp' => $otp
    ]);

    if (is_wp_error($done)) {
      return ['success' => false];
    }

    $isValid = isset($done->license) && $done->license === 'valid';

    return [
      'success' => $isValid,
      'completed' => true,
      'message' => $isValid ? 'Essential Addons ativado com sucesso!' : 'A ativação com o OTP falhou.',
    ];
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
