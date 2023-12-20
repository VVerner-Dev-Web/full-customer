<?php

namespace Full\Customer\Api;

use \FullCustomerController;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

defined('ABSPATH') || exit;

class Login extends FullCustomerController
{
  public static function registerRoutes(): void
  {
    $api = new self();

    register_rest_route(self::NAMESPACE, '/auth-token', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'processAuthTokenRequest'],
        'permission_callback' => '__return_true',
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/login/(?P<hash>[A-Z0-9]+)', [
      [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => [$api, 'processLogin'],
        'permission_callback' => '__return_true',
      ]
    ]);
  }

  public function processAuthTokenRequest(): WP_REST_Response
  {
    return new WP_REST_Response([
      'deprecated' => true
    ]);
  }

  public function processLogin(): WP_REST_Response
  {
    return new WP_REST_Response([
      'deprecated' => true
    ]);
  }
}
