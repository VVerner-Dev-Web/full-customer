<?php

namespace Full\Customer\Actions;

use FULL_CUSTOMER_Env;

defined('ABSPATH') || exit;

function insertFooterNote(): void
{
  require_once FULL_CUSTOMER_APP . '/views/footer/note.php';
}

function activationHook(): void
{
  $url = defined('FULL_CUSTOMER_ENV') && FULL_CUSTOMER_ENV === 'DEV' ?
    'https://full.dev' :
    'https://painel.fullstackagency.club';

  wp_remote_post($url . '/wp-json/full-customer/v1/analytics', [
    'sslverify' => false,
    'headers'   => ['x-full' => 'Jkd0JeCPm8Nx', 'Content-Type' => 'application/json'],
    'body'      => json_encode([
      'site_url'      => home_url(),
      'admin_email'   => get_bloginfo('admin_email'),
      'plugin_status' => 'active'
    ])
  ]);
}

function deactivationHook(): void
{
  $url = defined('FULL_CUSTOMER_ENV') && FULL_CUSTOMER_ENV === 'DEV' ?
    'https://full.dev' :
    'https://painel.fullstackagency.club';

  wp_remote_post($url . '/wp-json/full-customer/v1/analytics', [
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
  add_menu_page(
    'FULL.',
    'FULL.',
    'manage_options',
    'full-settings',
    'fullGetAdminPageView',
    fullGetImageUrl('icon.png')
  );
}

function adminEnqueueScripts(): void
{
  if (isFullsAdminPage()) :
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';
    $env     = new FULL_CUSTOMER_Env();

    wp_enqueue_style('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.css', [], '11.4.35');
    wp_enqueue_script('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.js', ['jquery'], '11.4.35', true);

    wp_enqueue_style('full-admin', $baseUrl . 'css/admin.css', [], FULL_CUSTOMER_VERSION);
    wp_enqueue_script('full-admin', $baseUrl . 'js/admin.js', ['jquery'], FULL_CUSTOMER_VERSION, true);
    wp_localize_script('full-admin', 'full_localize', [
      'rest_url'      => trailingslashit(rest_url()),
      'auth'          => wp_create_nonce('wp_rest'),
      'user_login'    => wp_get_current_user()->user_login,
      'dashboard_url' => $env->getFullDashboardApiUrl() . '-customer/v1/'
    ]);
  endif;
}

function upgradePlugin(): void
{
  $siteVersion = get_option('full-customer-version', '0.0.0');

  if (version_compare(FULL_CUSTOMER_VERSION, $siteVersion, '>') && !get_transient('full-upgrading')) :
    set_transient('full-upgrading', 1, MINUTE_IN_SECONDS);

    $upgradeVersions = apply_filters('full-versions-upgrades', []);

    foreach ($upgradeVersions as $pluginVersion) :
      if (version_compare($pluginVersion, $siteVersion, '>=')) :
        do_action('full-customer/upgrade/' . $pluginVersion);
      endif;
    endforeach;

    update_option('full-customer-version', FULL_CUSTOMER_VERSION, false);
  endif;
}
