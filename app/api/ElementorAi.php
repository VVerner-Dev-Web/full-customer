<?php

namespace Full\Customer\Api;

use \FullCustomerController;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

defined('ABSPATH') || exit;

class ElementorAi extends FullCustomerController
{
  public static function registerRoutes(): void
  {
    $api = new self();

    register_rest_route(self::NAMESPACE, '/elementor/ai', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'generate'],
        'permission_callback' => [$api, 'elementorPermissionCallback'],
      ]
    ]);
  }

  public function elementorPermissionCallback(): bool
  {
    return is_user_logged_in() && $this->hasElementor() && fullCustomer()->isServiceEnabled('full-ai-elementor');
  }

  public function generate(WP_REST_Request $request): WP_REST_Response
  {
    $full    = fullCustomer();
    $payload = [
      'site'      => site_url(),
      'prompt'    => $request->get_param('prompt'),
      'template'  => $request->get_param('template'),
    ];

    $url      = $full->getFullDashboardApiUrl() . '-customer/v1/ai';
    $request  = wp_remote_post($url, [
      'sslverify' => false,
      'body'      => $payload,
      'timeout'   => MINUTE_IN_SECONDS * 5
    ]);

    $response = wp_remote_retrieve_body($request);
    $response = json_decode($response);

    update_option('full/ai/quota', $response->quota);

    return new WP_REST_Response($response);
  }

  private function hasElementor(): bool
  {
    return class_exists('Full\Customer\Elementor\TemplateManager');
  }
}
