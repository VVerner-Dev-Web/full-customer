<?php

namespace Full\Customer\Login\Actions;

use Full\Customer\Login\Settings;

defined('ABSPATH') || exit;

function addMenuPages(array $menu): array
{
  $menu[] = [
    'name' => 'FULL.login',
    'endpoint' => 'full-login'
  ];

  return $menu;
}

function adminEnqueueScripts(): void
{
  if ('login' !== fullAdminPageEndpoint()) :
    return;
  endif;

  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  wp_enqueue_script('full-admin-login', $baseUrl . 'js/admin-login.js', ['jquery'], $version, true);
}

function updateLoginSettings(): void
{
  check_ajax_referer('full/widget/login-settings');

  $worker = new Settings();

  $worker->set('redirectAfterLogin', untrailingslashit(trim(filter_input(INPUT_POST, 'redirectAfterLogin'))));
  $worker->set('redirectAfterLogout', untrailingslashit(trim(filter_input(INPUT_POST, 'redirectAfterLogout'))));

  $worker->set('useSiteIdentity', filter_input(INPUT_POST, 'useSiteIdentity', FILTER_VALIDATE_BOOL));
  $worker->set('loginNavMenuItem', filter_input(INPUT_POST, 'loginNavMenuItem', FILTER_VALIDATE_BOOL));
  $worker->set('enableChangeLoginUrl', filter_input(INPUT_POST, 'enableChangeLoginUrl', FILTER_VALIDATE_BOOL));

  if ($worker->get('enableChangeLoginUrl')) :
    $worker->set('changedLoginUrl', untrailingslashit(trim(filter_input(INPUT_POST, 'changedLoginUrl'))));
  endif;

  wp_send_json_success();
}
