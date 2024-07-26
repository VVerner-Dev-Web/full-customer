<?php

namespace Full\Customer\Images\Actions;

use Full\Customer\Images\Settings;

defined('ABSPATH') || exit;

function addMenuPages(array $menu): array
{
  $menu[] = [
    'name' => 'FULL.images',
    'endpoint' => 'full-images'
  ];

  return $menu;
}

function adminEnqueueScripts(): void
{
  if ('images' === fullAdminPageEndpoint()) :
    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    wp_enqueue_script('full-admin-images', $baseUrl . 'js/admin-images.js', ['jquery'], $version, true);
  endif;
}

function updateSettings(): void
{
  check_ajax_referer('full/widget/image-settings');

  $worker = new Settings();

  $worker->set('useImagify', filter_input(INPUT_POST, 'useImagify', FILTER_VALIDATE_BOOL));
  $worker->set('enableUploadResize', filter_input(INPUT_POST, 'enableUploadResize', FILTER_VALIDATE_BOOL));
  $worker->set('enableSvgUpload', filter_input(INPUT_POST, 'enableSvgUpload', FILTER_VALIDATE_BOOL));
  $worker->set('resizeMaxSize', filter_input(INPUT_POST, 'resizeMaxSize', FILTER_VALIDATE_INT));

  wp_send_json_success();
}
