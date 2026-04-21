<?php

namespace FC\Services;

use WP_REST_Request;
use WP_REST_Response;

use FC\Fragments\DashboardFullPage;
use FC\Fragments\ChatFullPage;
use FC\Fragments\SkillsFullPage;

class Fragments
{
  private array $registry = [
    'DashboardFullPage' => DashboardFullPage::class,
    'ChatFullPage' => ChatFullPage::class,
    'SkillsFullPage' => SkillsFullPage::class,
  ];

  public function __construct()
  {
    fcRegisterRestRoute('POST', 'fragments', [$this, 'fragments']);
  }

  public function fragments(WP_REST_Request $request): WP_REST_Response
  {
    $requested_fragments = $request->get_param('fragments');

    if (!is_array($requested_fragments)) {
      return rest_ensure_response(['success' => false, 'error' => 'Invalid payload']);
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
