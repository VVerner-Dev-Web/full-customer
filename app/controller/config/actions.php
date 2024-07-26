<?php

namespace Full\Customer\Admin\Actions;

use Full\Customer\Admin\Settings;

defined('ABSPATH') || exit;

function addMenuPages(array $menu): array
{
  $menu[] = [
    'name' => 'FULL.config',
    'endpoint' => 'full-config',
  ];

  return $menu;
}

function updateSettings(): void
{
  check_ajax_referer('full/widget/admin-settings');

  $worker = new Settings();

  $worker->set('clearTopBar', filter_input(INPUT_POST, 'clearTopBar', FILTER_VALIDATE_BOOL));
  $worker->set('moveNotifications', filter_input(INPUT_POST, 'moveNotifications', FILTER_VALIDATE_BOOL));
  $worker->set('disableDashboardWidgets', filter_input(INPUT_POST, 'disableDashboardWidgets', FILTER_VALIDATE_BOOL));
  $worker->set('hideAdminBarOnFrontend', filter_input(INPUT_POST, 'hideAdminBarOnFrontend', FILTER_VALIDATE_BOOL));
  $worker->set('sidebarWidth', filter_input(INPUT_POST, 'sidebarWidth', FILTER_VALIDATE_INT));

  wp_send_json_success(['reload' => true]);
}
