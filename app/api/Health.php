<?php

namespace Full\Customer\Api;

use Full\Customer\Health\Controller;
use \FullCustomerController;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

defined('ABSPATH') || exit;

class Health extends FullCustomerController
{
  public static function registerRoutes(): void
  {
    $api = new self();

    register_rest_route(self::NAMESPACE, '/health', [
      [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => [$api, 'getHeathStats'],
        'permission_callback' => 'is_user_logged_in',
      ]
    ]);
  }

  public function getHeathStats(): WP_REST_Response
  {
    return new WP_REST_Response([
      'results' => (new Controller)->getResults(),
    ]);
  }
}
