<?php

defined('ABSPATH') || exit;

function fullGetAdminPageView(): void
{
  $page     = filter_input(INPUT_GET, 'page');
  $endpoint = $page ? str_replace('full-', '', $page) : '';
  $file     = FULL_CUSTOMER_APP . '/views/admin/' . $endpoint . '.php';

  if (file_exists($file)) :
    echo '<div class="wrap full-customer-page" id="fc-' . $endpoint . '">';
    include $file;
    echo '</div>';
  endif;
}

function fullGetImageUrl(string $image): string
{
  return trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/img/' . $image;
}

function isFullsAdminPage(): bool
{
  $page = filter_input(INPUT_GET, 'page');
  return strpos($page, 'full-') === 0;
}

/**
 * @SuppressWarnings(PHPMD.MissingImport)
 */
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
