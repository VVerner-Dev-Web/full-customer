<?php

namespace Full\Customer\Security\Actions;

use Full\Customer\Security\Settings;

defined('ABSPATH') || exit;

function addMenuPages(array $menu): array
{
  $menu[] = [
    'name' => 'FULL.firewall',
    'endpoint' => 'full-security'
  ];

  return $menu;
}

function adminEnqueueScripts(): void
{
  if ('security' !== fullAdminPageEndpoint()) :
    return;
  endif;

  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  wp_enqueue_script('full-admin-security', $baseUrl . 'js/admin-security.js', ['jquery'], $version, true);
}

function updateSettings(): void
{
  check_ajax_referer('full/widget/security-settings');

  $worker = new Settings();

  $worker->set('enableLastLoginColumn', filter_input(INPUT_POST, 'enableLastLoginColumn', FILTER_VALIDATE_BOOL));
  $worker->set('disableFeeds', filter_input(INPUT_POST, 'disableFeeds', FILTER_VALIDATE_BOOL));
  $worker->set('disableXmlrpc', filter_input(INPUT_POST, 'disableXmlrpc', FILTER_VALIDATE_BOOL));
  $worker->set('enablePasswordProtection', filter_input(INPUT_POST, 'enablePasswordProtection', FILTER_VALIDATE_BOOL));
  $worker->set('enableUsersOnlyMethod', filter_input(INPUT_POST, 'enableUsersOnlyMethod', FILTER_VALIDATE_BOOL));

  if ($worker->get('enablePasswordProtection')) :
    $worker->set('sitePassword', filter_input(INPUT_POST, 'sitePassword'));
  endif;

  wp_send_json_success();
}
