<?php

namespace Full\Customer\Actions;

defined('ABSPATH') || exit;

function insertFooterNote(): void
{
  $full = fullCustomer();
  $file = FULL_CUSTOMER_APP . '/views/footer/note.php';

  $settings = $full->get('whitelabel_settings');
  $enabled = is_array($settings) && isset($settings['allow_backlink']) ? $settings['allow_backlink'] !== 'no' : true;

  if ($enabled && file_exists($file)) :
    require_once $file;
  endif;
}

function notifyPluginError(): bool
{
  $error = get_option('full_customer_last_error');

  if (!$error) :
    return false;
  endif;

  $url  = getFullDashboardApiUrl('-customer/v1/error');

  wp_remote_post($url, [
    'sslverify' => false,
    'headers'   => [
      'Content-Type'  => 'application/json',
    ],
    'body'  => wp_json_encode([
      'site_url'  => home_url(),
      'error'     => $error,
      'version'   => FULL_CUSTOMER_VERSION
    ])
  ]);

  delete_option('full_customer_last_error');
  return true;
}

function initFullElementorTemplates(): void
{
  if (class_exists('\Elementor\Plugin')) :
    require_once FULL_CUSTOMER_APP . '/controller/elementor/hooks.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/actions.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/filters.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/TemplateManager.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/Importer.php';
    require_once FULL_CUSTOMER_APP . '/controller/elementor/Exporter.php';
  endif;
}
