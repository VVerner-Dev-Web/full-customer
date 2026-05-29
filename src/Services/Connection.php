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

    add_action('admin_notices', [$this, 'notices']);
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
    $anon = fcGetAnonymousUserConnection();

    return base64_encode(wp_json_encode([
      'fc_version'        => FULL_CUSTOMER_VERSION,
      'fc_mode'           => FULL_CUSTOMER_DEV ? 'dev' : 'prod',
      'wp_version'        => get_bloginfo('version'),
      'connection_email'  => is_user_logged_in() ? $user->getConnectionEmail() : ($anon ? $anon['connection_email'] : null),
      'wp_user_email'     => is_user_logged_in() ? $user->wp()->user_email : ($anon ? get_userdata($anon['user_id'])->user_email : null),
      'wp_site_url'       => trailingslashit(home_url()),
    ]));
  }

  public function notices(): void
  {
    $user = User::instance();

    if (!$user->isAdmin()) {
      return;
    }

    $fs = FileSystem::instance();
    $message = '
    <div class="fs-admin-notice__banner">
      <div class="fs-admin-notice__banner-imagem">
        <img src="' . $fs->getUrl('assets/images/plugue.png') . '" alt="Conectar" />
      </div>
      <div class="fs-admin-notice__banner-barra"></div>
      <div class="fs-admin-notice__banner-conteudo">
        <div class="fs-admin-notice__banner-textos">
          <p class="fs-admin-notice__banner-nome">FULL. Services</p>
          <p class="fs-admin-notice__banner-texto">{{text}}</p>
        </div>
        <a href="' . admin_url('admin.php?page=full') . '" class="fs-admin-notice__banner-btn">{{cta}}</a>
      </div>
    </div>';

    if (!$user->isConnected()) {
      delete_option('fc/automatic-connection');
      echo str_replace(
        ['{{cta}}', '{{text}}'],
        ['Conectar site', 'Seu usuário está desconectado. Para aproveitar todos os benefícios da FULL, conecte seu site à sua conta FULL.'],
        $message
      );
      return;
    }

    $auto = get_option('fc/automatic-connection');

    if ($auto) {
      delete_option('fc/automatic-connection');

      $replace = $auto === 'upgrade' ?
        ['Ver novidades', 'Atualizamos automaticamente sua conexão com o painel da FULL. Aproveite!'] :
        ['Ativar plugins PRO', 'Conectamos automaticamente seu site ao painel da FULL. Aproveite para ativar seus plugins agora mesmo!'];

      echo str_replace(['{{cta}}', '{{text}}'], $replace, $message);
      return;
    }
  }

  public function autoConnection(): void
  {
    $fs = FileSystem::instance();

    $connectionEmail = null;
    $connectionMode = '';

    if ($fs->isFile('conn.json')) {
      $json = $fs->getContents('conn.json') ?: '{}';
      $data = json_decode($json, true);
      $connectionEmail = isset($data['email']) && $data['email'] ? $data['email'] : null;
      $fs->delete('conn.json');

      $connectionMode = 'file';
    }

    $legacyConnection = get_option('_full_customer-connection_email');

    if ($legacyConnection) {
      $connectionEmail = $legacyConnection;

      delete_option('_full_customer-connection_email');
      delete_option('_full_customer-enabled_services');
      delete_option('_full_customer-dashboard_url');
      delete_option('_full_customer-previous-connect-site-check');

      $connectionMode = 'upgrade';
    }

    if ($connectionEmail) {
      $done = (new ConnectAccount())->handleConnection($connectionEmail);

      if ($done['success']) {
        update_option('fc/automatic-connection', $connectionMode);
      }
    }
  }
}
