<?php

defined('ABSPATH') || exit;

class FullCustomerConnection
{
  public function __construct()
  {
    add_action('wp_ajax_full/connect-site', [$this, 'connectSite']);
  }

  public function connectSite(): void
  {
    check_ajax_referer('full/connect-site');

    if (!current_user_can('manage_options')) {
      wp_send_json_error('Ops, você não tem permissão para fazer isso.');
    }

    $panelEmail = sanitize_email(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? '');

    if (!$panelEmail) {
      wp_send_json_error('Por favor, insira um e-mail válido.');
    }

    wp_send_json_success();
  }
}

new FullCustomerConnection();
