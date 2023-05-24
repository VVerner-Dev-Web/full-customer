<?php

namespace Full\Customer\Actions;

use Full\Customer\Backup\Controller;
use FullCustomer;

defined('ABSPATH') || exit;

function insertFooterNote(): void
{
  $full = new FullCustomer();

  if ($full->get('allow_backlink')) :
    require_once FULL_CUSTOMER_APP . '/views/footer/note.php';
  endif;
}

function insertAdminNotice(): void
{
  $full = new FullCustomer();

  if (!$full->hasDashboardUrl()) :
    require_once FULL_CUSTOMER_APP . '/views/admin/notice.php';
  endif;
}

function verifySiteConnection(): void
{
  $flag = 'previous-connect-site-check';
  $full = new FullCustomer();

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
  $full  = new FullCustomer();
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
  $full  = new FullCustomer();
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
  $full = new FullCustomer();

  add_submenu_page(
    'options-general.php',
    $full->getBranding('admin-page-name', 'FULL.'),
    $full->getBranding('admin-page-name', 'FULL.'),
    'manage_options',
    'full-connection',
    'fullGetAdminPageView'
  );
}

function adminEnqueueScripts(): void
{
  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';
  wp_enqueue_style('full-global-admin', $baseUrl . 'css/global-admin.css', [], $version);

  if (isFullsAdminPage()) :
    wp_enqueue_style('full-icons', 'https://painel.full.services/wp-content/plugins/full/app/assets/vendor/icon-set/style.css');
    wp_enqueue_style('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.css', [], '11.4.35');
    wp_enqueue_script('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.js', ['jquery'], '11.4.35', true);
    wp_enqueue_style('full-flickity', $baseUrl . 'vendor/flickity/flickity.min.css', [], '2.3.0');
    wp_enqueue_script('full-flickity', $baseUrl . 'vendor/flickity/flickity.min.js', ['jquery'], '2.3.0', true);
    wp_enqueue_style('full-admin', $baseUrl . 'css/admin.css', [], $version);
  endif;

  wp_enqueue_script('full-admin', $baseUrl . 'js/admin.js', ['jquery'], $version, true);
  wp_localize_script('full-admin', 'FULL', fullGetLocalize());
}

function upgradePlugin(): void
{
  $env = new FullCustomer();
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

  $full = new FullCustomer();
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
    require_once FULL_CUSTOMER_APP . '/elementor/hooks.php';
    require_once FULL_CUSTOMER_APP . '/elementor/actions.php';
    require_once FULL_CUSTOMER_APP . '/elementor/filters.php';

    require_once FULL_CUSTOMER_APP . '/elementor/TemplateManager.php';
    require_once FULL_CUSTOMER_APP . '/elementor/Importer.php';
    require_once FULL_CUSTOMER_APP . '/elementor/Exporter.php';
  endif;
}
