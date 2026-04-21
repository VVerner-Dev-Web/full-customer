<?php

namespace FC\Services;

use FC\FileSystem;
use FC\User;
use WP_REST_Request;
use WP_REST_Response;

class Connection
{
  public function __construct()
  {
    add_action('admin_init', [$this, 'autoConnection']);

    register_activation_hook(FULL_CUSTOMER_FILE, [$this, 'autoConnection']);
    register_activation_hook(FULL_CUSTOMER_FILE, [$this, 'activationAnalyticsHook']);
    register_deactivation_hook(FULL_CUSTOMER_FILE, [$this, 'deactivationAnalyticsHook']);

    add_action('admin_notices', [$this, 'notice']);

    fcRegisterRestRoute('POST', 'connect', [$this, 'connect']);
  }

  public static function getConnectionToken(): ?string
  {
    $user = User::instance();

    return base64_encode(wp_json_encode([
      'fc_version'        => FULL_CUSTOMER_VERSION,
      'fc_mode'           => FULL_CUSTOMER_DEV ? 'dev' : 'prod',
      'wp_version'        => get_bloginfo('version'),
      'connection_email'  => $user->getConnectionEmail(),
      'wp_site_url'       => trailingslashit(home_url()),
      'wp_user_email'     => $user->wp()->user_email,
    ]));
  }

  public function connect(WP_REST_Request $request): WP_REST_Response
  {
    $email = sanitize_email($request->get_param('email'));

    if (!is_email($email)) {
      return new WP_REST_Response([
        'error' => 'E-mail inválido ou ausente, por favor informe apenas o e-mail da sua conta FULL.'
      ]);
    }

    $success = $this->handleConnection($email);

    return new WP_REST_Response([
      'success' => $success['success'],
      'message' => $success ?
        'Conectado com sucesso! Vamos recarregar a página para que você possa aproveitar ao máximo sua conta FULL.' :
        $success['data']['message']
    ]);
  }

  public function notice(): void
  {
    $user = User::instance();

    if (!$user->isAdmin() || $user->isConnected()) {
      return;
    }

    wp_admin_notice('
      <h2>FULL. Services</h2>
      <p>Seu usuário está desconectado. Para aproveitar todos os benefícios da FULL. Conecte seu site a sua conta FULL.</p>
      <p><a class="button-primary" href="' . admin_url('admin.php?page=full') . '">Conectar site</a></p>
    ', [
      'type'               => 'warning',
      'additional_classes' => ['notice-alt'],
    ]);
  }

  public function activationAnalyticsHook(): void
  {
    fcDashboardAPI()->fetch('POST', 'analytics/fc-status/active');
  }

  public function deactivationAnalyticsHook(): void
  {
    fcDashboardAPI()->fetch('POST', 'analytics/fc-status/inactive');
  }

  public function autoConnection(): void
  {
    $fs = FileSystem::instance();
    $conn = $fs->isFile('conn.json');

    if (!$conn) {
      return;
    }

    $json = $fs->getContents('conn.json') ?: '{}';
    $data = json_decode($json, true);

    if (isset($data['email']) && $data['email']) {
      $this->handleConnection($data['email']);
    }

    $fs->delete('conn.json');
  }

  private function handleConnection(string $email): array
  {
    define('FULL_CUSTOMER_CONNECTION_EMAIL', $email);

    $exists = fcDashboardAPI()->fetch('GET', 'account/me');
    $success = $exists['success'] && $exists['data']['me']['site']['dashboardUrl'];

    if (!$success) {
      $connected = fcDashboardAPI()->fetch('POST', 'account/connect', [
        'login_url' => wp_login_url()
      ]);
      $success = $connected['success'];
    }

    if ($success) {
      $user = User::instance();
      $user->setConnectionEmail($email);
    }

    return [
      'success' => $success,
      'data'    => isset($connected) ? $connected : $exists
    ];
  }
}
