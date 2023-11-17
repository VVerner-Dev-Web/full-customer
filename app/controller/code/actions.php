<?php

namespace Full\Customer\Code\Actions;

use Full\Customer\Code\Settings;

defined('ABSPATH') || exit;

function addMenuPages(): void
{
  add_submenu_page(
    'full-connection',
    'FULL.code',
    'FULL.code',
    'edit_posts',
    'full-code',
    'fullGetAdminPageView'
  );
}

function adminEnqueueScripts(): void
{
  if ('code' !== fullAdminPageEndpoint()) :
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

  wp_enqueue_script('full-admin-code', $baseUrl . 'js/admin-code.js', ['jquery'], $version, true);
}

function enqueueFrontendCustomStyles(): void
{
  $worker = new Settings();

  if (!$worker->get('frontend-css')) :
    return;
  endif;

  echoCodeWithComments('<style id="full-frontend-css">' . $worker->get('frontend-css') . '</style>');
}

function enqueueAdminCustomStyles(): void
{
  $worker = new Settings();

  if (!$worker->get('admin-css')) :
    return;
  endif;

  echoCodeWithComments('<style id="full-admin-css">' . $worker->get('admin-css') . '</style>');
}

function enqueueHeadScripts(): void
{
  $worker = new Settings();

  if (!$worker->get('head-code')) :
    return;
  endif;

  echoCodeWithComments($worker->get('head-code'));
}

function enqueueBodyScripts(): void
{
  $worker = new Settings();

  if (!$worker->get('body-code')) :
    return;
  endif;

  echoCodeWithComments($worker->get('body-code'));
}

function enqueueFooterScripts(): void
{
  $worker = new Settings();

  if (!$worker->get('footer-code')) :
    return;
  endif;

  echoCodeWithComments($worker->get('footer-code'));
}


function updateInsertedCode()
{
  check_ajax_referer('full/widget/code/update-code');

  $key = filter_input(INPUT_POST, 'code');
  $code = wp_kses_post(filter_input(INPUT_POST, $key));

  $worker = new Settings();
  $worker->set($key, $code);

  wp_send_json_success();
}

function updateRobotsFile()
{
  check_ajax_referer('full/widget/code/update-robots');

  $key = filter_input(INPUT_POST, 'code');
  $code = wp_kses_post(filter_input(INPUT_POST, $key));

  file_put_contents(ABSPATH . '/robots.txt', $code);

  wp_send_json_success();
}

function echoCodeWithComments(string $code): void
{
  echo '<!-- scripts adicionados pelo FULL.code -->' . $code . '<!-- /scripts adicionados pelo FULL.code -->';
}
