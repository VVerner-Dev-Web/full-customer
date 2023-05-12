<?php

defined('ABSPATH') || exit;

function fullGetAdminPageView(): void
{
  $page     = filter_input(INPUT_GET, 'page');
  $endpoint = $page ? str_replace('full-', '', $page) : '';
  $file     = FULL_CUSTOMER_APP . '/views/admin/' . $endpoint . '.php';

  if (file_exists($file)) :
    include $file;
  endif;
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
  return strpos($page, 'full-') === 0;
}

function fullGetEnv(): string
{
  return (new FullCustomer)->getCurrentEnv();
}

function fullGetLocalize(): array
{
  $env     = new FullCustomer();

  return [
    'rest_url'      => trailingslashit(rest_url()),
    'auth'          => wp_create_nonce('wp_rest'),
    'user_login'    => wp_get_current_user()->user_login,
    'dashboard_url' => $env->getFullDashboardApiUrl() . '-customer/v1/',
    'site_url'      => site_url(),
    'store_url'     => 'https://full.services'
  ];
}

function fullGetSiteConnectionData()
{
  $full = new FullCustomer();
  $url  = $full->getFullDashboardApiUrl() . '-customer/v1/connect-site';

  $request  = wp_remote_get($url, [
    'sslverify' => false,
    'headers'   => [
      'Content-type' => 'application/json'
    ],
    'body'      => [
      'site_url' => site_url()
    ]
  ]);

  $response = wp_remote_retrieve_body($request);
  $response = json_decode($response);

  return $response;
}

function isSiteConnectedOnFull(): bool
{
  $connectionTest = fullGetSiteConnectionData();
  return $connectionTest && $connectionTest->success;
}

function fullGetTemplatesUrl(string $endpoint = ''): string
{
  return add_query_arg([
    'page'      => 'full-templates',
    'endpoint'  => $endpoint
  ], admin_url('admin.php'));
}
