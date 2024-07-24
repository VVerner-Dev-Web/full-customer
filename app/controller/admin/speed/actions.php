<?php

namespace Full\Customer\Speed\Actions;

use Full\Customer\Speed\Settings;

defined('ABSPATH') || exit;

function updateSettings(): void
{
  check_ajax_referer('full/widget/speed-settings');

  $worker = new Settings();

  $worker->set('disableGutenberg', filter_input(INPUT_POST, 'disableGutenberg', FILTER_VALIDATE_BOOL));
  $worker->set('disableBlockWidgets', filter_input(INPUT_POST, 'disableBlockWidgets', FILTER_VALIDATE_BOOL));
  $worker->set('disableDeprecatedComponents', filter_input(INPUT_POST, 'disableDeprecatedComponents', FILTER_VALIDATE_BOOL));
  $worker->set('reduceHeartbeat', filter_input(INPUT_POST, 'reduceHeartbeat', FILTER_VALIDATE_BOOL));
  $worker->set('disablePostRevisions', filter_input(INPUT_POST, 'disablePostRevisions', FILTER_VALIDATE_BOOL));

  wp_send_json_success();
}
