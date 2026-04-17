<?php

namespace FC\Services;

use FC\FileSystem;
use FC\User;

class Connection
{
  public function __construct()
  {
    add_action('admin_init', [$this, 'autoConnection']);

    register_activation_hook(FULL_CUSTOMER_FILE, [$this, 'autoConnection']);
    register_activation_hook(FULL_CUSTOMER_FILE, [$this, 'activationAnalyticsHook']);
    register_deactivation_hook(FULL_CUSTOMER_FILE, [$this, 'deactivationAnalyticsHook']);

    add_action('admin_notices', [$this, 'notice']);
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
      define('FULL_CUSTOMER_CONNECTION_EMAIL', $data['email']);
    }

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
      $user->setConnectionEmail($data['email']);
    }

    $fs->delete('conn.json');
  }
}
