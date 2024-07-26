<?php

namespace Full\Customer\WhatsApp\Actions;

use Full\Customer\WhatsApp\Settings;

defined('ABSPATH') || exit;

function addMenuPages(array $menu): array
{
  $menu[] = [
    'name' => 'FULL.whatsapp',
    'endpoint' => 'full-whatsapp'
  ];

  return $menu;
}

function addMetaBoxes(): void
{
  $worker = new Settings();
  if (!$worker->isButtonEnabled()) :
    return;
  endif;

  add_meta_box(
    'full-whatsapp',
    'FULL.whatsapp',
    'fullGetMetaBox',
    get_post_types(['public' => true]),
    'side',
    'high'
  );
}

function maybeUpdateSinglePostSettings($postId): void
{
  if (wp_is_post_revision($postId) || !filter_input(INPUT_POST, 'fullUpdatingWhatsApp', FILTER_VALIDATE_BOOL)) {
    return;
  }

  $full = filter_input(INPUT_POST, 'full', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
  $full = wp_parse_args($full, [
    'whatsappDisplay' => '',
    'whatsappNumber' => '',
    'whatsappMessage' => '',
  ]);

  update_post_meta($postId, 'full/whatsappDisplay', trim($full['whatsappDisplay']));
  update_post_meta($postId, 'full/whatsappNumber', trim($full['whatsappNumber']));
  update_post_meta($postId, 'full/whatsappMessage', trim($full['whatsappMessage']));
}

function adminEnqueueScripts(): void
{
  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  wp_enqueue_script('full-mask', $baseUrl . 'vendor/jquery-mask/jquery.mask.min.js', ['jquery'], '1.14.16', true);
  wp_enqueue_script('full-admin-whatsapp', $baseUrl . 'js/admin-whatsapp.js', ['jquery'], $version, true);
}


function updateSettings(): void
{
  check_ajax_referer('full/widget/whatsapp-settings');

  $worker = new Settings();
  $worker->set('enableGlobalButton', filter_input(INPUT_POST, 'enableGlobalButton', FILTER_VALIDATE_BOOL));
  $worker->set('whatsappNumber', filter_input(INPUT_POST, 'whatsappNumber'));
  $worker->set('whatsappMessage', filter_input(INPUT_POST, 'whatsappMessage'));
  $worker->set('whatsappPosition', filter_input(INPUT_POST, 'whatsappPosition'));
  $worker->set('whatsappLogo', filter_input(INPUT_POST, 'whatsappLogo'));
  $worker->set('whatsappLogoSize', filter_input(INPUT_POST, 'whatsappLogoSize', FILTER_VALIDATE_INT));
  $worker->set('displayCondition', sanitize_title(filter_input(INPUT_POST, 'displayCondition')));
  $worker->set('validCpt', filter_input(INPUT_POST, 'validCpt', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []);

  wp_send_json_success();
}

function addButton(): void
{
  $worker = new Settings();

  if (
    $worker->isButtonEnabledForGlobal() ||
    $worker->isButtonEnabledForPostType(get_post_type()) ||
    (is_single() && $worker->isButtonEnabledForSinglePost(get_the_ID()))
  ) :
    require_once FULL_CUSTOMER_APP . '/views/footer/whatsapp-button.php';
  endif;
}

function addButtonStyles(): void
{
  $worker = new Settings();
  if (!$worker->isButtonEnabled()) :
    return;
  endif;

  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  wp_enqueue_style('full-whatsapp', $baseUrl . 'css/whatsapp-button.css', [], $version);
}
