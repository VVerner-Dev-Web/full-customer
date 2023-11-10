<?php

namespace Full\Customer\Images\Actions;

use Full\Customer\Images\Settings;

defined('ABSPATH') || exit;

function addMenuPages(): void
{
  add_submenu_page(
    'full-connection',
    'FULL.images',
    'FULL.images',
    'edit_posts',
    'full-images',
    'fullGetAdminPageView'
  );
}

function adminEnqueueScripts(): void
{
  $worker = new Settings();

  if ('images' === fullAdminPageEndpoint() || $worker->get('enableMediaReplacement') && 'attachment' === get_post_type()) :
    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    wp_enqueue_script('full-admin-images', $baseUrl . 'js/admin-images.js', ['jquery'], $version, true);
  endif;
}

function updateSettings(): void
{
  check_ajax_referer('full/widget/image-settings');

  $worker = new Settings();

  $worker->set('enableUploadResize', filter_input(INPUT_POST, 'enableUploadResize', FILTER_VALIDATE_BOOL));
  $worker->set('enableSvgUpload', filter_input(INPUT_POST, 'enableSvgUpload', FILTER_VALIDATE_BOOL));
  $worker->set('enableMediaReplacement', filter_input(INPUT_POST, 'enableMediaReplacement', FILTER_VALIDATE_BOOL));
  $worker->set('resizeMaxWidth', filter_input(INPUT_POST, 'resizeMaxWidth'));
  $worker->set('resizeMaxHeight', filter_input(INPUT_POST, 'resizeMaxHeight'));
  $worker->set('resizeQuality', filter_input(INPUT_POST, 'resizeQuality', FILTER_VALIDATE_INT));

  wp_send_json_success();
}
