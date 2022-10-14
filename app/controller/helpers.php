<?php

defined('ABSPATH') || exit;

function fullGetAdminPageView(): void
{
  $endpoint = isset($_GET['page']) && $_GET['page'] ? str_replace('full-', '', $_GET['page']) : '';
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
  $endpoint = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : '';
  return strpos($endpoint, 'full-') === 0;
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
