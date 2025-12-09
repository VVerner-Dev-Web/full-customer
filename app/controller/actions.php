<?php

namespace Full\Customer\Actions;

use Full\Customer\License;
use FullCustomerUpdate;
use WP_REST_Request;

defined('ABSPATH') || exit;

function insertFooterNote(): void
{
  $full = fullCustomer();
  $file = FULL_CUSTOMER_APP . '/views/footer/note.php';

  $settings = $full->get('whitelabel_settings');
  $enabled = is_array($settings) && isset($settings['allow_backlink']) ? $settings['allow_backlink'] !== 'no' : true;

  if ($enabled && file_exists($file)) :
    require_once $file;
  endif;
}

function insertAdminNotice(): void
{
  $full = fullCustomer();
  $file = FULL_CUSTOMER_APP . '/views/admin/notice.php';

  if (!$full->hasDashboardUrl() && file_exists($file)) :
    require_once $file;
  endif;
}

function forceLicenseCheck(): void
{
  if (filter_input(INPUT_GET, 'full') === 'verify_license') {
    License::updateStatus();

    wp_safe_redirect(esc_url(remove_query_arg('full')));
    exit;
  }

  if (filter_input(INPUT_GET, 'full') === 'repo_clear') {
    @unlink(FullCustomerUpdate::repositoryFilename());

    global $wpdb;

    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%full/plugin-license%'");

    wp_safe_redirect(esc_url(remove_query_arg('full')));
    exit;
  }
}

function verifySiteConnection(): void
{
  $flag = 'previous-connect-site-check';
  $full = fullCustomer();

  if ($full->get($flag) || $full->hasDashboardUrl()) :
    return;
  endif;

  $response = fullGetSiteConnectionData();

  if ($response && $response->success) :
    $full->set('connection_email', sanitize_email($response->connection_email));
    $full->set('dashboard_url', esc_url($response->dashboard_url));
  endif;

  $full->set($flag, 1);
}

function activationAnalyticsHook(): void
{
  $full  = fullCustomer();
  $url   = $full->getFullDashboardApiUrl() . '-customer/v1/analytics';

  wp_remote_post($url, [
    'sslverify' => false,
    'headers'   => ['x-full' => 'Jkd0JeCPm8Nx', 'Content-Type' => 'application/json'],
    'body'      => wp_json_encode([
      'site_url'      => home_url(),
      'admin_email'   => get_bloginfo('admin_email'),
      'plugin_status' => 'active'
    ])
  ]);
}

function deactivationAnalyticsHook(): void
{
  $full  = fullCustomer();
  $url   = $full->getFullDashboardApiUrl() . '-customer/v1/analytics';

  wp_remote_post($url, [
    'sslverify' => false,
    'headers'   => ['x-full' => 'Jkd0JeCPm8Nx', 'Content-Type' => 'application/json'],
    'body'      => wp_json_encode([
      'site_url'      => home_url(),
      'admin_email'   => get_bloginfo('admin_email'),
      'plugin_status' => 'inactive'
    ])
  ]);
}

function addMenuPage(): void
{
  $full = fullCustomer();

  add_menu_page(
    $full->getBranding('admin-page-name', 'FULL.services'),
    $full->getBranding('admin-page-name', 'FULL.services'),
    'manage_options',
    'full-connection',
    'fullGetAdminPageView',
    'data:image/svg+xml;base64,' . base64_encode(fullFileSystem()->get_contents(plugin_dir_url(FULL_CUSTOMER_FILE) . 'app/assets/img/menu-novo.svg')),
    0
  );

  $connectionOk   = fullIsCorrectlyConnected();
  $cls = $connectionOk ? 'success' : 'error';
  $text = $connectionOk ? 'conectado' : 'desconectado';

  add_submenu_page(
    'full-connection',
    'Conexão',
    'Conexão <span class="full-badge full-' . $cls . '">' . $text . '</span>',
    'manage_options',
    'full-connection',
    'fullGetAdminPageView'
  );

  $status = License::status();
  $cls = $status['plan'] ? 'full' : 'error';
  $text = $status['plan'] ? $status['plan'] : 'seja PRO';

  add_submenu_page(
    'full-connection',
    'FULL.PRO',
    'FULL.PRO <span class="full-badge full-' . sanitize_title($cls) . '">' . esc_attr($text) . '</span>',
    'manage_options',
    'full-widgets',
    'fullGetAdminPageView'
  );

  add_submenu_page(
    'full-connection',
    'Planos',
    'Planos',
    'manage_options',
    'full-store',
    'fullGetAdminPageView'
  );

  $widgets = apply_filters('full-customer/active-widgets-menu', []);

  uasort($widgets, function ($a, $b) {
    return strcmp($a['name'], $b['name']);
  });

  foreach ($widgets as $widget) :
    add_submenu_page(
      'full-connection',
      $widget['name'],
      $widget['name'],
      'edit_posts',
      $widget['endpoint'],
      'fullGetAdminPageView'
    );
  endforeach;
}

function adminEnqueueScripts(): void
{
  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  if (isFullsAdminPage()) :
    wp_enqueue_style('full-icons', 'https://painel.full.services/wp-content/plugins/full/app/assets/vendor/icon-set/style.css', [], '1.0.0');
    wp_enqueue_style('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.css', [], '11.4.35');
    wp_enqueue_style('full-flickity', $baseUrl . 'vendor/flickity/flickity.min.css', [], '2.3.0');
    wp_enqueue_style('full-magnific-popup', $baseUrl . 'vendor/magnific-popup/magnific-popup.min.css', [], '1.0.0');
    wp_enqueue_style('full-admin', $baseUrl . 'css/admin.css', [], $version);

    wp_enqueue_script('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.js', ['jquery'], '11.4.35', true);
    wp_enqueue_script('full-flickity', $baseUrl . 'vendor/flickity/flickity.min.js', ['jquery'], '2.3.0', true);
    wp_enqueue_script('full-magnific-popup', $baseUrl . 'vendor/magnific-popup/magnific-popup.min.js', ['jquery'], '1.0.0', true);
  endif;

  wp_enqueue_style('full-global-admin', $baseUrl . 'css/global-admin.css', [], $version);
  wp_enqueue_script('full-admin', $baseUrl . 'js/admin.js', ['jquery'], $version, true);
  wp_localize_script('full-admin', 'FULL', fullGetLocalize());

  if (current_user_can('manage_options')) {
    $id = wp_generate_uuid4();

    wp_enqueue_style('full-staff', $baseUrl . 'css/staff.css', [], $version);
    wp_enqueue_script('full-staff', $baseUrl . 'js/staff.js', ['jquery'], $version, true);
    wp_localize_script('full-staff', 'FULL_STAFF', [
      'wpPluginsUrl' => admin_url('plugins.php'),
      'repository' => add_query_arg([
        'action'  => 'full/staff/repository',
        'nonce'   => wp_create_nonce('full/staff/repository')
      ], admin_url('admin-ajax.php')),
      'installPlugin' => add_query_arg([
        'id'      => $id,
        'action'  => 'full/staff/install-plugin',
        'nonce'   => wp_create_nonce('full/staff/install-plugin')
      ], admin_url('admin-ajax.php')),
      'installPluginProgress' => add_query_arg([
        'id'      => $id,
        'action'  => 'full/staff/install-plugin/progress',
        'nonce'   => wp_create_nonce('full/staff/install-plugin/progress')
      ], admin_url('admin-ajax.php'))
    ]);
  }
}

function upgradePlugin(): void
{
  $env = fullCustomer();
  $siteVersion = $env->get('version') ? $env->get('version') : '0.0.0';

  if (version_compare(FULL_CUSTOMER_VERSION, $siteVersion, '>') && !get_transient('full-upgrading')) :
    set_transient('full-upgrading', 1, MINUTE_IN_SECONDS);

    $upgradeVersions = apply_filters('full-versions-upgrades', []);

    foreach ($upgradeVersions as $pluginVersion) :
      if (version_compare($pluginVersion, $siteVersion, '>=')) :
        do_action('full-customer/upgrade/' . $pluginVersion);
      endif;
    endforeach;

    $env->set('version', FULL_CUSTOMER_VERSION);
  endif;
}

function notifyPluginError(): bool
{
  $error = get_option('full_customer_last_error');

  if (!$error) :
    return false;
  endif;

  $full = fullCustomer();
  $url  = $full->getFullDashboardApiUrl() . '-customer/v1/error';

  wp_remote_post($url, [
    'sslverify' => false,
    'headers'   => [
      'Content-Type'  => 'application/json',
    ],
    'body'  => wp_json_encode([
      'site_url'  => home_url(),
      'error'     => $error,
      'version'   => FULL_CUSTOMER_VERSION
    ])
  ]);

  delete_option('full_customer_last_error');
  return true;
}

function initFullAccessWidget(): void
{
  if (fullCustomer()->isServiceEnabled('full-access')) :
    require_once FULL_CUSTOMER_APP . '/controller/access/Authentication.php';
    require_once FULL_CUSTOMER_APP . '/controller/access/RegistrationFields.php';
    require_once FULL_CUSTOMER_APP . '/controller/access/Interaction.php';
  endif;
}

function startWidgets(): void
{
  if (fullCustomer()->isServiceEnabled('full-login')) :
    require_once FULL_CUSTOMER_APP . '/controller/login/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/login/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/login/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/login/Url.php';
    require_once FULL_CUSTOMER_APP . '/controller/login/Identity.php';
    require_once FULL_CUSTOMER_APP . '/controller/login/Menu.php';
    require_once FULL_CUSTOMER_APP . '/controller/login/LogoutRedirect.php';
    require_once FULL_CUSTOMER_APP . '/controller/login/LoginRedirect.php';
  endif;

  if (fullCustomer()->isServiceEnabled('full-email')) :
    require_once FULL_CUSTOMER_APP . '/controller/email/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/email/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/email/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/email/SMTP.php';
  endif;

  if (fullCustomer()->isServiceEnabled('full-images')) :
    require_once FULL_CUSTOMER_APP . '/controller/images/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/images/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/images/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/images/SvgUpload.php';
    require_once FULL_CUSTOMER_APP . '/controller/images/UploadResizer.php';
    require_once FULL_CUSTOMER_APP . '/controller/images/ImageOptimization.php';
  endif;

  if (fullCustomer()->isServiceEnabled('full-config')) :
    require_once FULL_CUSTOMER_APP . '/controller/config/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/AdminInterface.php';

    require_once FULL_CUSTOMER_APP . '/controller/config/seo/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/seo/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/seo/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/seo/Posts.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/seo/Links.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/seo/Comments.php';

    require_once FULL_CUSTOMER_APP . '/controller/config/code/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/code/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/code/Settings.php';

    require_once FULL_CUSTOMER_APP . '/controller/config/speed/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/speed/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/speed/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/speed/DeprecatedComponents.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/speed/BlockBasedFeatures.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/speed/Revisions.php';
    require_once FULL_CUSTOMER_APP . '/controller/config/speed/Heartbeat.php';
  endif;

  if (fullCustomer()->isServiceEnabled('full-security')) :
    require_once FULL_CUSTOMER_APP . '/controller/security/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/security/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/security/Settings.php';

    require_once FULL_CUSTOMER_APP . '/controller/security/Feeds.php';
    require_once FULL_CUSTOMER_APP . '/controller/security/LastLoginColumn.php';
    require_once FULL_CUSTOMER_APP . '/controller/security/PasswordProtection.php';
    require_once FULL_CUSTOMER_APP . '/controller/security/UsersOnlyMode.php';
  endif;

  if (fullCustomer()->isServiceEnabled('full-woocommerce')) :
    require_once FULL_CUSTOMER_APP . '/controller/woocommerce/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/woocommerce/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/woocommerce/Settings.php';

    require_once FULL_CUSTOMER_APP . '/controller/woocommerce/secret-coupon/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/woocommerce/secret-coupon/Frontend.php';
    require_once FULL_CUSTOMER_APP . '/controller/woocommerce/secret-coupon/Admin.php';

    require_once FULL_CUSTOMER_APP . '/controller/woocommerce/checkout-redirect/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/woocommerce/checkout-redirect/Admin.php';
    require_once FULL_CUSTOMER_APP . '/controller/woocommerce/checkout-redirect/Frontend.php';

    if (function_exists('WC')) :
      require_once FULL_CUSTOMER_APP . '/controller/woocommerce/EstimateMode.php';
      require_once FULL_CUSTOMER_APP . '/controller/woocommerce/HidePrices.php';
      require_once FULL_CUSTOMER_APP . '/controller/woocommerce/OrderReceived.php';
      require_once FULL_CUSTOMER_APP . '/controller/woocommerce/ProductCustomTab.php';
      require_once FULL_CUSTOMER_APP . '/controller/woocommerce/ProductReviews.php';
      require_once FULL_CUSTOMER_APP . '/controller/woocommerce/TestPaymentGateway.php';
      require_once FULL_CUSTOMER_APP . '/controller/woocommerce/AutocompleteOrders.php';
      require_once FULL_CUSTOMER_APP . '/controller/woocommerce/WhatsAppCheckout.php';
    endif;
  endif;

  if (fullCustomer()->isServiceEnabled('full-elementor-crm')) :
    require_once FULL_CUSTOMER_APP . '/controller/elementor-crm/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor-crm/Hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor-crm/Leads.php';
  endif;

  if (fullCustomer()->isServiceEnabled('full-whatsapp')) :
    require_once FULL_CUSTOMER_APP . '/controller/whatsapp/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/whatsapp/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/whatsapp/Settings.php';
  endif;

  if (fullCustomer()->isServiceEnabled('full-ai-copy')) :
    require_once FULL_CUSTOMER_APP . '/controller/copy/Hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/copy/TextGenerator.php';
  endif;

  if (fullCustomer()->isServiceEnabled('full-analytics')) :
    require_once FULL_CUSTOMER_APP . '/controller/analytics/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/analytics/Database.php';
    require_once FULL_CUSTOMER_APP . '/controller/analytics/Assets.php';
    require_once FULL_CUSTOMER_APP . '/controller/analytics/PageView.php';
    require_once FULL_CUSTOMER_APP . '/controller/analytics/Conversion.php';
    require_once FULL_CUSTOMER_APP . '/controller/analytics/ConversionTracker.php';
    require_once FULL_CUSTOMER_APP . '/controller/analytics/API.php';
    require_once FULL_CUSTOMER_APP . '/controller/analytics/Admin.php';
  endif;

  if (fullCustomer()->isServiceEnabled('full-social-proof')) :
    require_once FULL_CUSTOMER_APP . '/controller/social-proof/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/social-proof/RecentPurchases.php';
    require_once FULL_CUSTOMER_APP . '/controller/social-proof/RecentVisitors.php';
    require_once FULL_CUSTOMER_APP . '/controller/social-proof/Admin.php';
  endif;
}

function initFullElementorTemplates(): void
{
  if (class_exists('\Elementor\Plugin')) :
    require_once FULL_CUSTOMER_APP . '/controller/elementor/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/filters.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/TemplateManager.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/Importer.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/Exporter.php';
  endif;
}

function initFullElementorAddons(): void
{
  if (class_exists('\Elementor\Plugin')) :
    require_once FULL_CUSTOMER_APP . '/controller/elementor-addons/Registrar.php';
  endif;
}


function adminFooter(): void
{
  require_once FULL_CUSTOMER_APP . '/views/wpadmin-footer.php';
}

function staffRepository(): void
{
  if (!current_user_can('manage_options') || !wp_verify_nonce(filter_input(INPUT_GET, 'nonce'), 'full/staff/repository')) {
    wp_send_json_error();
  }

  $dir = [];
  foreach (FullCustomerUpdate::fetchDirectory() as $item) {
    $dir[] = [
      'plugin' => $item->plugin,
      'name' => $item->name
    ];
  }

  wp_send_json_success($dir);
}

function enqueueInstallationProgress(string $processId, string $plugin, string $message): void
{
  $progress = getInstallationProgress($processId);

  if (!isset($progress[$plugin])) {
    $progress[$plugin] = [];
  }

  $progress[$plugin][] = $message;

  set_transient($processId, $progress, HOUR_IN_SECONDS);
}

function getInstallationProgress(string $processId): array
{
  return get_transient($processId) ?: [];
}

function staffInstallPlugin(): void
{
  if (!current_user_can('manage_options') || !wp_verify_nonce(filter_input(INPUT_GET, 'nonce'), 'full/staff/install-plugin')) {
    wp_send_json_error('Sem permissão');
  }

  require_once ABSPATH . 'wp-admin/includes/plugin.php';

  global $wp_filesystem;

  if (!is_a($wp_filesystem, 'WP_Filesystem_Base')) {
    include_once(ABSPATH . 'wp-admin/includes/file.php');
    $creds = request_filesystem_credentials(site_url());
    wp_filesystem($creds);
  }

  $id  = filter_input(INPUT_GET, 'id') ?? uniqid();
  $dir = FullCustomerUpdate::fetchDirectory(cache: false);
  $key = filter_input(INPUT_POST, 'plugin') ?? '';
  $plugin = $dir[$key] ?? null;

  if (!$plugin) {
    wp_send_json_error('Plugin não localizado');
  }

  enqueueInstallationProgress($id, $key, 'Iniciando instalação do plugin ' . $plugin->name . '...');

  if ($plugin->dependencies) {
    enqueueInstallationProgress($id, $key, 'Verificando dependências...');

    foreach ($plugin->dependencies as $dep) {
      $request = new WP_REST_Request('POST', '/wp/v2/plugins');
      $request->set_param('slug', $dep);
      $request->set_param('status', 'active');
      $request->set_param('context', 'edit');

      enqueueInstallationProgress($id, $key, 'Instalando dependência ' . $dep);
      rest_do_request($request);
    }
  }

  enqueueInstallationProgress($id, $key, 'Dependências validadas. Iniciando processo do plugin principal');

  $recoveryLink = ' <a href="' . $plugin->package . '">Baixar plugin</a> ';
  $package = download_url($plugin->package, 300);

  if (is_wp_error($package)) {
    wp_send_json_error('[download] ' . $package->get_error_message() .  $recoveryLink);
  }

  enqueueInstallationProgress($id, $key, 'Arquivo baixado. Iniciando descompactação...');

  $workingDir = $wp_filesystem->wp_content_dir() . 'upgrade/' . $plugin->slug;

  if ($wp_filesystem->is_dir($workingDir)) {
    $wp_filesystem->delete($workingDir, true);
  }

  wp_mkdir_p($workingDir);

  $done = unzip_file($package, $workingDir);

  if (is_wp_error($done)) {
    wp_send_json_error('[unzip] ' . $done->get_error_message() .  $recoveryLink);
  }

  enqueueInstallationProgress($id, $key, 'Arquivo descompactado. Iniciando a transferência...');

  $wp_filesystem->delete($package);

  $done = copy_dir($workingDir, WP_PLUGIN_DIR);
  if (is_wp_error($done)) {
    wp_send_json_error('[copy] ' . $done->get_error_message() .  $recoveryLink);
  }

  $wp_filesystem->delete($workingDir, true);

  $pluginActivationPath = trailingslashit(WP_PLUGIN_DIR) . $plugin->plugin;

  enqueueInstallationProgress($id, $key, 'Arquivo transferido. Solicitando ativação do plugin no WordPress');

  if (!is_plugin_active($pluginActivationPath)) {
    activate_plugin($pluginActivationPath);
  }

  wp_send_json_success();
}

function staffInstallPluginProgress(): void
{
  $id  = filter_input(INPUT_GET, 'id') ?? uniqid();
  $key = filter_input(INPUT_POST, 'plugin') ?? '';

  $progress = getInstallationProgress($id)[$key] ?? [];

  wp_send_json_success('> ' . implode('<br>> ', $progress));
}
