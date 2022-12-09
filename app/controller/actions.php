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
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';
  wp_enqueue_style('full-global-admin', $baseUrl . 'css/global-admin.css', [], FULL_CUSTOMER_VERSION);

  if (isFullsAdminPage()) :
    $env     = new FullCustomer();

    wp_enqueue_style('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.css', [], '11.4.35');
    wp_enqueue_script('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.js', ['jquery'], '11.4.35', true);

    wp_enqueue_style('full-admin', $baseUrl . 'css/admin.css', [], FULL_CUSTOMER_VERSION);
    wp_enqueue_script('full-admin', $baseUrl . 'js/admin.js', ['jquery'], FULL_CUSTOMER_VERSION, true);
    wp_localize_script('full-admin', 'full_localize', [
      'rest_url'      => trailingslashit(rest_url()),
      'auth'          => wp_create_nonce('wp_rest'),
      'user_login'    => wp_get_current_user()->user_login,
      'dashboard_url' => $env->getFullDashboardApiUrl() . '-customer/v1/',
      'site_url'      => site_url()
    ]);
  endif;
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

function createCronBackup(): bool
{
  if (!wp_doing_cron()) :
    return false;
  endif;

  $controller = new Controller;
  $controller->createBackup();

  return true;
}

function createAsyncCronBackup(): bool
{
  createCronBackup();

  $full  = new FullCustomer();
  $url   = $full->getFullDashboardApiUrl() . '-customer/v1/backup-webhook';

  wp_remote_post($url, [
    'sslverify' => false,
    'headers'   => [
      'Content-Type'  => 'application/json',
    ],
    'body'      => json_encode([
      'site_url'      => home_url()
    ])
  ]);

  return true;
}

function restoreAsyncBackup(string $backupId): void
{
  $controller = new Controller;
  $controller->restoreBackup($backupId);

  $full  = new FullCustomer();
  $url   = $full->getFullDashboardApiUrl() . '-customer/v1/restore-webhook';

  wp_remote_post($url, [
    'sslverify' => false,
    'headers'   => [
      'Content-Type'  => 'application/json',
    ],
    'body'      => json_encode([
      'site_url'      => home_url()
    ])
  ]);
}
