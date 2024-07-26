<?php

namespace Full\Customer\WooCommerce\Actions;

use Full\Customer\WooCommerce\Settings;

defined('ABSPATH') || exit;

function addMenuPages(array $menu): array
{
  $menu[] = [
    'name' => 'FULL.woocommerce',
    'endpoint' => 'full-woocommerce'
  ];

  return $menu;
}

function adminEnqueueScripts(): void
{
  if ('woocommerce' !== fullAdminPageEndpoint()) :
    return;
  endif;

  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  wp_enqueue_style('full-codemirror', $baseUrl . 'vendor/codemirror/codemirror.min.css', [], '6.1.0');
  wp_enqueue_script('full-codemirror', $baseUrl . 'vendor/codemirror/codemirror.min.js', ['jquery'], '6.1.0', true);
  wp_enqueue_script('full-codemirror-css', $baseUrl . 'vendor/codemirror/css.min.js', ['jquery'], '6.1.0', true);
  wp_enqueue_script('full-codemirror-htmlmixed', $baseUrl . 'vendor/codemirror/htmlmixed.min.js', ['jquery'], '6.1.0', true);
  wp_enqueue_script('full-codemirror-javascript', $baseUrl . 'vendor/codemirror/javascript.min.js', ['jquery'], '6.1.0', true);
  wp_enqueue_script('full-codemirror-markdown', $baseUrl . 'vendor/codemirror/markdown.min.js', ['jquery'], '6.1.0', true);
  wp_enqueue_script('full-codemirror-xml', $baseUrl . 'vendor/codemirror/xml.min.js', ['jquery'], '6.1.0', true);

  wp_enqueue_script('full-mask', $baseUrl . 'vendor/jquery-mask/jquery.mask.min.js', ['jquery'], '1.14.16', true);

  wp_enqueue_script('full-admin-code', $baseUrl . 'js/admin-code.js', ['jquery'], $version, true);
  wp_enqueue_script('full-admin-woocommerce', $baseUrl . 'js/admin-woocommerce.js', ['jquery'], $version, true);
}

function updateSettings(): void
{
  check_ajax_referer('full/widget/woocommerce-settings');

  $worker = new Settings();

  $worker->set('enableTestPaymentGateway', filter_input(INPUT_POST, 'enableTestPaymentGateway', FILTER_VALIDATE_BOOL));
  $worker->set('autocompleteProcessingOrders', filter_input(INPUT_POST, 'autocompleteProcessingOrders', FILTER_VALIDATE_BOOL));
  $worker->set('hidePrices', filter_input(INPUT_POST, 'hidePrices', FILTER_VALIDATE_BOOL));
  $worker->set('enableEstimateOrders', filter_input(INPUT_POST, 'enableEstimateOrders', FILTER_VALIDATE_BOOL));
  $worker->set('disableProductReviews', filter_input(INPUT_POST, 'disableProductReviews', FILTER_VALIDATE_BOOL));
  $worker->set('enableProductCustomTab', filter_input(INPUT_POST, 'enableProductCustomTab', FILTER_VALIDATE_BOOL));
  $worker->set('enableWhatsAppCheckout', filter_input(INPUT_POST, 'enableWhatsAppCheckout', FILTER_VALIDATE_BOOL));

  if ($worker->get('enableProductCustomTab')) :
    $worker->set('customProductTabName', filter_input(INPUT_POST, 'customProductTabName'));
    $worker->set('customProductTabContent', filter_input(INPUT_POST, 'customProductTabContent'));
  endif;

  if ($worker->get('enableWhatsAppCheckout')) :
    $worker->set('whatsAppCheckoutNumber', filter_input(INPUT_POST, 'whatsAppCheckoutNumber'));
    $worker->set('whatsAppCheckoutMessage', sanitize_textarea_field(filter_input(INPUT_POST, 'whatsAppCheckoutMessage')));
  endif;

  $worker->set('orderReceivedPageCustomCode', filter_input(INPUT_POST, 'orderReceivedPageCustomCode'));

  wp_send_json_success();
}
