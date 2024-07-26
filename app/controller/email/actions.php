<?php

namespace Full\Customer\Email\Actions;

use Full\Customer\Email\Settings;

defined('ABSPATH') || exit;

function addMenuPages(array $menu): array
{
  $menu[] = [
    'name' => 'FULL.smtp',
    'endpoint' => 'full-email'
  ];

  return $menu;
}

function adminEnqueueScripts(): void
{
  if ('email' !== fullAdminPageEndpoint()) :
    return;
  endif;

  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  wp_enqueue_script('full-admin-email', $baseUrl . 'js/admin-email.js', ['jquery'], $version, true);
}

function updateEmailSettings(): void
{
  check_ajax_referer('full/widget/email-settings');

  $worker = new Settings();

  $worker->set('senderName', filter_input(INPUT_POST, 'senderName'));
  $worker->set('senderEmail', filter_input(INPUT_POST, 'senderEmail'), FILTER_VALIDATE_EMAIL);
  $worker->set('enableSmtp', filter_input(INPUT_POST, 'enableSmtp', FILTER_VALIDATE_BOOL));
  $worker->set('smtpDebug', filter_input(INPUT_POST, 'smtpDebug', FILTER_VALIDATE_BOOL));
  $worker->set('smtpHost', filter_input(INPUT_POST, 'smtpHost'));
  $worker->set('smtpPort', filter_input(INPUT_POST, 'smtpPort', FILTER_VALIDATE_INT));
  $worker->set('smtpSecurity', filter_input(INPUT_POST, 'smtpSecurity'));
  $worker->set('smtpUser', filter_input(INPUT_POST, 'smtpUser'));
  $worker->set('smtpPassword', filter_input(INPUT_POST, 'smtpPassword'));

  wp_send_json_success();
}

function sendTestEmail(): void
{
  check_ajax_referer('full/widget/email-test');

  wp_mail(
    filter_input(INPUT_POST, 'recipient', FILTER_VALIDATE_EMAIL),
    get_bloginfo('name') . ' - Email teste',
    'Esta é uma mensagem deste disparada pelo site ' . get_bloginfo('name')
  );

  wp_send_json_success();
}
