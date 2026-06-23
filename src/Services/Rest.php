<?php

namespace FC\Services;

use FC\FileSystem;
use FC\Fragments\DashboardFullPage;
use FC\Fragments\SkillsFullPage;
use FC\SkillRepository;
use WP_REST_Request;
use WP_REST_Response;

class Rest
{
  public function __construct()
  {
    add_action('init', [$this, 'actions']);

    fcRegisterRestRoute('POST', 'fragments', [$this, 'fragments']);

    fcRegisterRestRoute('GET', 'skills', [$this, 'skills']);

    fcRegisterRestRoute('POST', 'local-license-processor', [$this, 'localLicenseProcessor'], '__return_true');
  }

  public function actions(): void
  {
    $processed = [];
    $skills = SkillRepository::instance()->all();

    foreach ($skills as $skill) {
      foreach ($skill->actions() as $action) {
        $this->register_action_recursive($action, $processed);
      }
    }
  }

  private function register_action_recursive($action, array &$processed)
  {
    $class = is_string($action) ? $action : get_class($action);

    if (in_array($class, $processed)) return;

    $instance = is_string($action) ? new $action() : $action;

    if ($instance->isShell()) {
      foreach ($instance->inShellActions() as $subActionClass) {
        $this->register_action_recursive($subActionClass, $processed);
      }
    } else {
      fcRegisterRestRoute(
        $instance->getRestMethod(),
        $instance->getRestRoute(),
        [$instance, 'restHandler']
      );
    }

    $processed[] = $class;
  }

  public function fragments(WP_REST_Request $request): WP_REST_Response
  {
    $requested_fragments = $request->get_param('fragments');

    if (!is_array($requested_fragments)) {
      return rest_ensure_response(['success' => false, 'error' => 'Invalid payload']);
    }

    $registry = [
      'DashboardFullPage' => DashboardFullPage::class,
      'SkillsFullPage' => SkillsFullPage::class,
    ];

    $response = [];

    foreach ($requested_fragments as $key => $args) {
      if (isset($registry[$key])) {
        $class = $registry[$key];
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

  public function skills(): WP_REST_Response
  {
    $list = [];

    foreach (SkillRepository::instance()->all() as $skill) {
      $actions = [];

      foreach ($skill->actions() as $action) {
        if (!$action->isAvailable()) continue;
        $actions[] = $action->getPromptArgs();
      }

      $list[] = [
        'imageUrl'          => FileSystem::instance()->getUrl($skill->getIcon()),
        'name'              => $skill->getName(),
        'id'                => $skill::ID,
        'isDefault'         => $skill->isDefault(),
        'shortDescription'  => $skill->getShortDescription(),
        'isAvailable'       => $skill->isAvailable(),
        'isSoon'            => $skill->isSoon(),
        'actions'           => $actions
      ];
    }

    return rest_ensure_response([
      'success' => true,
      'skills'  => $list
    ]);
  }

  public function localLicenseProcessor(WP_REST_Request $request): WP_REST_Response
  {
    $plugin = $request->get_param('plugin');

    set_transient('fc/local-license-processor/' . $plugin, wp_json_encode($request->get_params()), MINUTE_IN_SECONDS);

    return rest_ensure_response([
      'success' => true,
    ]);
  }
}
