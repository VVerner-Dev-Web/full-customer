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
