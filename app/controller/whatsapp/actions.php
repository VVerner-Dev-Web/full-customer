<?php

namespace Full\Customer\WhatsApp\Actions;

use Full\Customer\WhatsApp\Settings;

defined('ABSPATH') || exit;

function addMenuPages(): void
{
  add_submenu_page(
    'full-connection',
    'FULL.whatsapp',
    'FULL.whatsapp',
    'edit_posts',
    'full-whatsapp',
    'fullGetAdminPageView'
  );
}

function adminEnqueueScripts(): void
{
  if ('whatsapp' !== fullAdminPageEndpoint()) :
    return;
  endif;

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

  wp_send_json_success();
}

function addButton(): void
{
  $worker = new Settings();
  if (!$worker->get('enableGlobalButton')) :
    return;
  endif;

  require_once FULL_CUSTOMER_APP . '/views/footer/whatsapp-button.php';
}

function addButtonStyles(): void
{
  $worker = new Settings();
  if (!$worker->get('enableGlobalButton')) :
    return;
  endif;

  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  wp_enqueue_style('full-whatsapp', $baseUrl . 'css/whatsapp-button.css', [], $version);
}
