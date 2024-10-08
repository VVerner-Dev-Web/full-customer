<?php

use Full\Customer\License;

defined('ABSPATH') || exit;

function fullIsCorrectlyConnected(): bool
{
  $code = (int) get_transient('full/connected');
  $code = 0;

  if (!$code) :
    $code = isSiteConnectedOnFull() && fullCustomer()->hasDashboardUrl() ? 1 : -1;
    set_transient('full/connected', $code, DAY_IN_SECONDS);
  endif;

  return $code > 0;
}

function fullCustomer(): FullCustomer
{
  return new FullCustomer();
}

function fullGetMetaBox($post, array $metaBox): void
{
  $endpoint = str_replace('full-', '', $metaBox['id']);
  $file = FULL_CUSTOMER_APP . '/views/metaboxes/' . $endpoint . '.php';
  if (file_exists($file)) :
    include $file;
  endif;
}

function fullGetAdminPageView(): void
{
  $file = FULL_CUSTOMER_APP . '/views/admin/' . fullAdminPageEndpoint() . '.php';
  if (file_exists($file)) :
    include $file;
  endif;
}

function fullAdminPageEndpoint(): string
{
  $page     = filter_input(INPUT_GET, 'page');
  return $page ? str_replace('full-', '', $page) : '';
}

function fullGetImageUrl(string $image): string
{
  return trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/img/' . $image;
}

function getFullAssetsVersion(): string
{
  return 'PRD' === fullGetEnv() ? FULL_CUSTOMER_VERSION : uniqid();
}

function isFullsAdminPage(): bool
{
  $page = filter_input(INPUT_GET, 'page');
  return $page !== null && strpos($page, 'full-') === 0;
}

function fullGetEnv(): string
{
  return fullCustomer()->getCurrentEnv();
}

function fullGetLocalize(): array
{
  $env     = fullCustomer();

  return [
    'rest_url'      => trailingslashit(rest_url()),
    'auth'          => wp_create_nonce('wp_rest'),
    'user_login'    => wp_get_current_user()->user_login,
    'dashboard_url' => $env->getFullDashboardApiUrl() . '-customer/v1/',
    'site_url'      => site_url(),
    'store_url'     => 'https://full.services',
    'ai_icon'       => fullGetImageUrl('icon-logo-full-ai.png'),
    'full_pro'      => License::isActive(),
    'enabled_services' => array_values($env->getEnabledServices()),
  ];
}

function fullGetSiteConnectionData()
{
  $response = get_transient('full/site-connection-data');

  if (!$response || 'full-connection' === filter_input(INPUT_GET, 'page')) :
    $full = fullCustomer();
    $url  = $full->getFullDashboardApiUrl() . '-customer/v1/connect-site';

    $request  = wp_remote_get($url, [
      'sslverify' => false,
      'headers'   => ['Content-type' => 'application/json'],
      'body'      => ['site_url' => site_url()]
    ]);

    $response = wp_remote_retrieve_body($request);
    $response = json_decode($response);

    set_transient('full/site-connection-data', $response, DAY_IN_SECONDS);
  endif;

  return $response;
}

function isSiteConnectedOnFull(): bool
{
  $connectionTest = fullGetSiteConnectionData();

  if ($connectionTest && $connectionTest->success) :
    fullCustomer()->set('dashboard_url', $connectionTest->dashboard_url);
  endif;

  return $connectionTest && $connectionTest->success;
}

function fullGetTemplatesUrl(string $endpoint = ''): string
{
  return esc_url(add_query_arg([
    'page'      => 'full-templates',
    'endpoint'  => $endpoint
  ], admin_url('admin.php')));
}

function fullJsonEncode($data): string
{
  return wp_slash(wp_json_encode(
    $data,
    JSON_UNESCAPED_LINE_TERMINATORS | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
  ));
}

function fullFileSystem()
{
  global $wp_filesystem;

  require_once(ABSPATH . '/wp-admin/includes/file.php');
  WP_Filesystem();

  return $wp_filesystem;
}
