<?php

namespace Full\Customer\Images\Actions;

use Full\Customer\Images\MediaReplacement;
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
  global $pagenow;

  $worker = new Settings();

  if (
    'images' === fullAdminPageEndpoint() ||
    $worker->get('enableMediaReplacement') && 'attachment' === get_post_type() ||
    $worker->get('enableMediaReplacement') && 'upload.php' === $pagenow
  ) :
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
  $worker->set('enableMediaReplacement', filter_input(INPUT_POST, 'enableMediaReplacement', FILTER_VALIDATE_BOOL));
  $worker->set('resizeMaxSize', filter_input(INPUT_POST, 'resizeMaxSize', FILTER_VALIDATE_INT));

  wp_send_json_success();
}

function replaceImage(): void
{
  $replace = filter_input(INPUT_POST, 'replace', FILTER_VALIDATE_INT);
  $original = filter_input(INPUT_POST, 'original', FILTER_VALIDATE_INT);

  if ($original === $replace) :
    wp_send_json_error();
  endif;

  MediaReplacement::replaceMedia($original, $replace);

  wp_send_json_success();
}
