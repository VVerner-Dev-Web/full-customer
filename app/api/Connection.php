<?php defined('ABSPATH') || exit;

class FULL_CUSTOMER_Connection extends WP_REST_Controller
{
  private const NAMESPACE = 'full-customer';
  private $env;

  public function __construct()
  {
    $this->env = new FULL_CUSTOMER();
  }

  public static function registerRoutes(): void
  {
    $api = new self();

    register_rest_route(self::NAMESPACE, '/connect', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'connectSite'],
        'permission_callback' => 'is_user_logged_in',
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/disconnect', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'disconnectSite'],
        'permission_callback' => 'is_user_logged_in',
      ]
    ]);
  }

  public function connectSite(WP_REST_Request $request): WP_REST_Response
  {
    $params = $request->get_json_params();

    $this->env->set('connection_email', sanitize_email($params['connection_email']));
    $this->env->set('dashboard_url', esc_url($params['dashboard_url']));

    return new WP_REST_Response(['connected' => true]);
  }

  public function disconnectSite(): WP_REST_Response
  {
    $this->env->set('connection_email', '');
    $this->env->set('dashboard_url', '');

    return new WP_REST_Response(['connected' => false]);
  }
}
