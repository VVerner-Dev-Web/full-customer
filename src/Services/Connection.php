<?php

namespace FC\Services;

use FC\Actions\ConnectAccount;
use FC\FileSystem;
use FC\User;

class Connection
{
  public function __construct()
  {
    register_activation_hook(FULL_CUSTOMER_FILE, [$this, 'autoConnection']);

    add_action('admin_init', [$this, 'autoConnection']);

    add_action('admin_notices', [$this, 'notice']);
    add_filter('plugin_row_meta', [$this, 'pluginRowMeta'], 10, 2);

    add_filter('http_request_args', [$this, 'filterRequestArgs'], PHP_INT_MAX, 2);
  }

  public function filterRequestArgs(array $args, $url): array
  {
    if (is_string($url) && $url && strpos($url, FULL_CUSTOMER_API_DOMAIN) !== false) {
      $args['reject_unsafe_urls'] = !FULL_CUSTOMER_DEV;
      $args['sslverify'] = !FULL_CUSTOMER_DEV;
      $args['headers']['X-Full-Signature'] = $this->getConnectionToken();
    }

    return $args;
  }

  public function pluginRowMeta(array $meta, string $plugin): array
  {
    if ($plugin === plugin_basename(FULL_CUSTOMER_FILE)) {
      $pageUrl = esc_url(admin_url('options-general.php?page=full'));

      foreach ($meta as $key => $action) {
        if (strpos($action, 'open-plugin-details-modal') !== false) {
          unset($meta[$key]);
          break;
        }
      }

      $meta[] = '<a href="' . $pageUrl .  '">Configurações</a>';
      $meta[] = User::instance()->isConnected() ? 'Site conectado!' : '<a href="' . $pageUrl . '">Conectar site</a>';
    }

    return $meta;
  }

  private function getConnectionToken(): ?string
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

    FileSystem::instance()->include('views/wp/connection-notice.php');
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
      (new ConnectAccount())->handleConnection($data['email']);
    }

    $fs->delete('conn.json');
  }
}
