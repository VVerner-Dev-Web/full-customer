<?php

namespace FC\WordPress;

use FC\Fragments\DashboardFullPage;
use FC\Fragments\ChatFullPage;
use FC\Fragments\SkillsFullPage;
use FC\User;
use WP_REST_Request;
use WP_REST_Response;

class Rest
{
  private array $registry = [
    'DashboardFullPage' => DashboardFullPage::class,
    'ChatFullPage' => ChatFullPage::class,
    'SkillsFullPage' => SkillsFullPage::class,
  ];

  public function __construct()
  {
    add_action('rest_api_init', [$this, 'routes']);
  }

  public function routes(): void
  {
    register_rest_route('fc/v1', '/fragments', [
      'methods'  => 'POST',
      'callback' => [$this, 'dispatch'],
      'permission_callback' => [$this, 'permissionCallback']
    ]);

    // TODO: pensar numa separação de rests porque vai ter muito...
    register_rest_route('fc/v1', '/connect', [
      'methods'  => 'POST',
      'callback' => [$this, 'connect'],
      'permission_callback' => [$this, 'permissionCallback']
    ]);
  }

  public function connect(WP_REST_Request $request): WP_REST_Response
  {
    $email = sanitize_email($request->get_param('email'));

    if (!is_email($email)) {
      return new WP_REST_Response([
        'error' => 'E-mail inválido ou ausente, por favor informe apenas o e-mail da sua conta FULL.'
      ]);
    }

    define('FULL_CUSTOMER_CONNECTION_EMAIL', $email);

    $connected = fcDashboardAPI()->fetch('POST', 'account/connect', [
      'login_url' => wp_login_url()
    ]);

    if (!$connected['success']) {
      return new WP_REST_Response([
        'success' => false,
        'message' => $connected['message']
      ]);
    }

    if ($connected['success']) {
      User::instance()->setConnectionEmail($email);
    }

    return new WP_REST_Response([
      'success' => true,
      'message' => 'Conectado com sucesso! Vamos recarregar a página para que você possa aproveitar ao máximo sua conta FULL.'
    ]);
  }

  public function permissionCallback(WP_REST_Request $request): bool
  {
    return current_user_can('manage_options');
  }

  public function dispatch(WP_REST_Request $request)
  {
    $requested_fragments = $request->get_param('fragments');

    if (!is_array($requested_fragments)) {
      return ['success' => false, 'error' => 'Invalid payload'];
    }

    $response = [];

    foreach ($requested_fragments as $key => $args) {
      if (isset($this->registry[$key])) {
        $class = $this->registry[$key];
        $response[$key] = (new $class($args))->render();
      } else {
        $response[$key] = "";
      }
    }

    return rest_ensure_response([
      'success'   => true,
      'fragments' => $response
    ]);
  }
}
