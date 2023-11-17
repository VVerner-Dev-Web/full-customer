<?php

namespace Full\Customer\Actions;

use Full\Customer\Backup\Controller;

defined('ABSPATH') || exit;

function insertFooterNote(): void
{
  $full = fullCustomer();
  $file = FULL_CUSTOMER_APP . '/views/footer/note.php';

  if ($full->get('allow_backlink') && file_exists($file)) :
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

function duplicatorNotice(): void
{
  $file = FULL_CUSTOMER_APP . '/views/admin/notice-duplicator-{status}.php';

  $error  = filter_input(INPUT_GET, 'full_duplicator_error');
  $postId = filter_input(INPUT_GET, 'full_duplicator_post_id', FILTER_VALIDATE_INT);
  $post   = $postId ? get_post($postId) : null;

  $errorFile = str_replace('{status}', 'error', $file);
  $successFile = str_replace('{status}', 'success', $file);

  if ($error && file_exists($errorFile)) :
    require_once $errorFile;
  endif;

  if ($post && file_exists($successFile)) :
    require_once $successFile;
  endif;
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
    'body'      => json_encode([
      'site_url'      => home_url(),
      'admin_email'   => get_bloginfo('admin_email'),
      'plugin_status' => 'active'
    ])
  ]);

  $full->set('allow_backlink', true);
}

function deactivationAnalyticsHook(): void
{
  $full  = fullCustomer();
  $url   = $full->getFullDashboardApiUrl() . '-customer/v1/analytics';

  wp_remote_post($url, [
    'sslverify' => false,
    'headers'   => ['x-full' => 'Jkd0JeCPm8Nx', 'Content-Type' => 'application/json'],
    'body'      => json_encode([
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
    trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/img/menu.png',
    0
  );

  add_submenu_page(
    'full-connection',
    'Extensões',
    'Extensões',
    'manage_options',
    'full-widgets',
    'fullGetAdminPageView'
  );

  add_submenu_page(
    'full-connection',
    'Bot Store',
    'Bot Store',
    'manage_options',
    'full-store',
    'fullGetAdminPageView'
  );
}

function adminEnqueueScripts(): void
{
  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  if (isFullsAdminPage()) :
    wp_enqueue_style('full-icons', 'https://painel.full.services/wp-content/plugins/full/app/assets/vendor/icon-set/style.css');
    wp_enqueue_style('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.css', [], '11.4.35');
    wp_enqueue_style('full-flickity', $baseUrl . 'vendor/flickity/flickity.min.css', [], '2.3.0');
    wp_enqueue_style('full-magnific-popup', $baseUrl . 'vendor/magnific-popup/magnific-popup.min.css', [], '1.0.0');
    wp_enqueue_style('full-admin', $baseUrl . 'css/admin.css', [], $version);

    wp_enqueue_script('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.js', ['jquery'], '11.4.35', true);
    wp_enqueue_script('full-flickity', $baseUrl . 'vendor/flickity/flickity.min.js', ['jquery'], '2.3.0', true);
    wp_enqueue_script('full-magnific-popup', $baseUrl . 'vendor/magnific-popup/magnific-popup.min.js', ['jquery'], '1.0.0', true);
  endif;

  wp_enqueue_style('full-global-admin', $baseUrl . 'css/global-admin.css', [], $version);

  if ('store' === fullAdminPageEndpoint()) :
    wp_enqueue_script('full-store', $baseUrl . 'js/admin-store.js', ['jquery'], $version, true);
  endif;

  wp_enqueue_script('full-admin', $baseUrl . 'js/admin.js', ['jquery'], $version, true);
  wp_localize_script('full-admin', 'FULL', fullGetLocalize());
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

function createCronBackup(bool $forceBackup = false): bool
{
  if (!wp_doing_cron() && !$forceBackup) :
    return false;
  endif;

  $controller = new Controller;
  $controller->createBackup();

  return true;
}

function createAsyncCronBackup(): bool
{
  return createCronBackup(true);
}

function restoreAsyncBackup(string $backupId, string $remoteBackupFile, string $remoteBackupId): void
{
  $controller = new Controller;
  $controller->restoreBackup($backupId, $remoteBackupFile, $remoteBackupId);
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
    'body'  => json_encode([
      'site_url'  => home_url(),
      'error'     => $error
    ])
  ]);

  delete_option('full_customer_last_error');
  return true;
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

function initFullLoginWidget(): void
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
}

function initFullEmailWidget(): void
{
  if (fullCustomer()->isServiceEnabled('full-email')) :
    require_once FULL_CUSTOMER_APP . '/controller/email/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/email/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/email/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/email/SMTP.php';
  endif;
}


function initFullImagesWidget(): void
{
  if (fullCustomer()->isServiceEnabled('full-images')) :
    require_once FULL_CUSTOMER_APP . '/controller/images/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/images/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/images/Settings.php';
    require_once FULL_CUSTOMER_APP . '/controller/images/SvgUpload.php';
    require_once FULL_CUSTOMER_APP . '/controller/images/MediaReplacement.php';
    require_once FULL_CUSTOMER_APP . '/controller/images/UploadResizer.php';
  endif;
}

function initFullCodeWidget(): void
{
  if (fullCustomer()->isServiceEnabled('full-code')) :
    require_once FULL_CUSTOMER_APP . '/controller/code/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/code/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/code/Settings.php';
  endif;
}
