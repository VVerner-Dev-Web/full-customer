<?php

namespace Full\Customer\Api;

use Full\Customer\Elementor\Importer;
use Full\Customer\Elementor\TemplateManager;
use \FullCustomerController;
use stdClass;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

defined('ABSPATH') || exit;

class Elementor extends FullCustomerController
{
  public static function registerRoutes(): void
  {
    $api = new self();

    register_rest_route(self::NAMESPACE, '/elementor/install/(?P<item_id>[0-9\-]+)', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'install'],
        'permission_callback' => [$api, 'permissionCallback'],
      ]
    ]);
  }

  public function permissionCallback(): bool
  {
    return is_user_logged_in() && $this->hasElementor();
  }

  public function install(WP_REST_Request $request): WP_REST_Response
  {
    $itemId = (int) $request->get_param('item_id');
    $item   = TemplateManager::instance()->getItem($itemId);

    if (!$item?->canBeInstalled) :
      return new WP_REST_Response(['error' => 'O item selecionado não pode ser instalado.']);
    endif;

    $template = json_decode(file_get_contents($item->file), true);
    $template['page_title']  = $item->title;

    if (!isset($template['type'])) :
      $template['type']  = 'page';
    endif;

    $importer = new Importer;
    $data     = $importer->get_data($template);

    $postId = ('page' === $request->get_param('mode')) ?
      $importer->create_page($data) :
      $importer->import_in_library($data);

    if (is_wp_error($postId)) :
      return new WP_REST_Response([
        'error' => $postId->get_error_message(),
      ]);
    endif;

    return new WP_REST_Response([
      'postId'    => $postId,
      'editUrl'   => get_edit_post_link($postId, 'internal'),
      'visitUrl'  => get_permalink($postId)
    ]);
  }

  private function hasElementor(): bool
  {
    return class_exists('Full\Customer\Elementor\TemplateManager');
  }
}
