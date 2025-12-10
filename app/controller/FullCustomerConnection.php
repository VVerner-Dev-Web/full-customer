<?php

defined('ABSPATH') || exit;

class FullCustomerConnection
{
  public function __construct()
  {
    add_action('admin_notices', [$this, 'connectionNotice']);
    add_action('wp_ajax_full/connect-site', [$this, 'connectSite']);
  }

  public function connectionNotice(): void
  {
    if (!current_user_can('manage_options')) {
      return;
    }

    if (isFullConnected()) {
      return;
    }

    $pageUrl = esc_url(admin_url('admin.php?page=full-connection'));
    wp_admin_notice('
      <h2>Bem vindo a FULL.</h2>
      <p>Estamos quase lá! Para aproveitar todos os benefícios da FULL. Conecte seu site a sua conta FULL.</p>
      <p><a class="button-primary" href="' . $pageUrl . '">Conectar site</a></p>
    ', [
      'type'               => 'warning',
      'additional_classes' => ['notice-alt'],
    ]);
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

    $url = getFullDashboardApiUrl('-customer/v1/connect-site');
    $response = wp_remote_post($url, [
      'headers'   => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
      'timeout'   => 15,
      'body'      => wp_json_encode([
        'customer' => $panelEmail,
      ])
    ]);

    if (is_wp_error($response)) {
      wp_send_json_error('Erro ao conectar ao painel FULL. Por favor, tente novamente mais tarde.');
    }

    $response = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($response['error'])) {
      wp_send_json_error($response['error']);
    }

    set_transient('full/site-connection-data', (object) $response, DAY_IN_SECONDS);

    wp_send_json_success('Site conectado com sucesso ao painel FULL!');
  }
}

new FullCustomerConnection();
