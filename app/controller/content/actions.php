<?php

namespace Full\Customer\Content\Actions;

use Full\Customer\Content\Settings;

defined('ABSPATH') || exit;

function addMenuPages(): void
{
  add_submenu_page(
    'full-connection',
    'FULL.content',
    'FULL.content',
    'edit_posts',
    'full-content',
    'fullGetAdminPageView'
  );
}


function updateSettings(): void
{
  check_ajax_referer('full/widget/content-settings');

  $worker = new Settings();

  $worker->set('enableContentDuplication', filter_input(INPUT_POST, 'enableContentDuplication', FILTER_VALIDATE_BOOL));
  $worker->set('disableComments', filter_input(INPUT_POST, 'disableComments', FILTER_VALIDATE_BOOL));
  $worker->set('redirect404ToHomepage', filter_input(INPUT_POST, 'redirect404ToHomepage', FILTER_VALIDATE_BOOL));
  $worker->set('openExternalLinkInNewTab', filter_input(INPUT_POST, 'openExternalLinkInNewTab', FILTER_VALIDATE_BOOL));
  $worker->set('publishMissingSchedulePosts', filter_input(INPUT_POST, 'publishMissingSchedulePosts', FILTER_VALIDATE_BOOL));

  wp_send_json_success();
}
