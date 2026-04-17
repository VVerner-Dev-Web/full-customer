<?php

namespace FC\WordPress;

use FC\Fragments\DashboardFullPage;
use FC\Fragments\ChatFullPage;
use FC\Fragments\SkillsFullPage;
use WP_REST_Request;

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
