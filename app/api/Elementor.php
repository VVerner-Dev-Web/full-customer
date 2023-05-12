<?php

namespace Full\Customer\Api;

use Full\Customer\Elementor\Exporter;
use Full\Customer\Elementor\Importer;
use Full\Customer\Elementor\TemplateManager;
use FullCustomer;
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

    register_rest_route(self::NAMESPACE, '/elementor/install', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'install'],
        'permission_callback' => [$api, 'permissionCallback'],
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/elementor/sync', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'sync'],
        'permission_callback' => [$api, 'permissionCallback'],
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/elementor/send-to-cloud', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'builderSendToCloud'],
        'permission_callback' => [$api, 'permissionCallback'],
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/elementor/send-to-cloud/(?P<post_id>[0-9\-]+)', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'sendToCloud'],
        'permission_callback' => [$api, 'permissionCallback'],
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/elementor/delete-from-cloud/(?P<item_id>[0-9\-]+)', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'deleteFromCloud'],
        'permission_callback' => [$api, 'permissionCallback'],
      ]
    ]);
  }

  public function sync(): WP_REST_Response
  {
    global $wpdb;

    $sql = "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_full/cloud/%'";
    $wpdb->query($sql);

    return new WP_REST_Response();
  }

  public function permissionCallback(): bool
  {
    return is_user_logged_in() && $this->hasElementor();
  }

  public function install(WP_REST_Request $request): WP_REST_Response
  {
    $item   = $request->get_param('item');
    $itemId = (int) $item['id'];
    $origin = sanitize_title($item['origin']);
    $mode   = sanitize_title($request->get_param('mode'));

    return ('template' === $origin) ?
      $this->installTemplate($itemId, $mode) :
      $this->installCloud($itemId, $mode);
  }

  private function installTemplate(int $itemId, string $mode): WP_REST_Response
  {
    $item   = TemplateManager::instance()->getItem($itemId);

    if (!$item || !$item->canBeInstalled) :
      return new WP_REST_Response(['error' => 'O item selecionado não pode ser instalado.']);
    endif;

    $template = $this->downloadJson($item->fileUrl);
    if (!$template) :
      return new WP_REST_Response(['error' => 'O item selecionado não foi localizado.']);
    endif;

    if ('builder' === $mode) :
      return new WP_REST_Response(['builder' => $template]);
    endif;

    $template['page_title']  = $item->title;
    $template['title']  = $item->title;

    if (!isset($template['type'])) :
      $template['type']  = 'page';
    endif;

    $importer = new Importer;
    $data     = $importer->get_data($template);

    $postId = ('page' === $mode) ?
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

  private function installCloud(int $itemId, string $mode): WP_REST_Response
  {
    $item     = TemplateManager::instance()->getCloudItem($itemId);
    $template = $this->downloadJson($item->fileUrl);

    if (!$template) :
      return new WP_REST_Response(['error' => 'O item selecionado não foi localizado.']);
    endif;

    if ('builder' === $mode) :
      return new WP_REST_Response(['builder' => $template]);
    endif;

    $template['page_title']  = $item->title;
    $template['title']  = $item->title;

    if (!isset($template['type'])) :
      $template['type']  = 'page';
    endif;

    $importer = new Importer;
    $data     = $importer->get_data($template);

    $postId = ('page' === $mode) ?
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

  public function builderSendToCloud(WP_REST_Request $request): WP_REST_Response
  {
    $full   = new FullCustomer();

    $type    = $request->get_param('templateType') ? $request->get_param('templateType') : 'page';
    $content = $request->get_param('templateContent');

    if (isset($content['id'])) :
      $content = [$content];
    endif;

    $payload = [
      'site'  => site_url(),
      'title' => $request->get_param('templateName'),
      'type'  => $type,
      'json'  => wp_slash(json_encode(
        compact('type', 'content'),
        JSON_UNESCAPED_LINE_TERMINATORS | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
      ))
    ];

    $url  = $full->getFullDashboardApiUrl() . '-customer/v1/template/cloud';

    $request  = wp_remote_post($url, ['sslverify' => false, 'body' => $payload]);
    $response = wp_remote_retrieve_body($request);
    $response = json_decode($response);

    return new WP_REST_Response($response);
  }

  public function sendToCloud(WP_REST_Request $request): WP_REST_Response
  {
    $full   = new FullCustomer();
    $postId = (int) $request->get_param('post_id');

    $payload = [
      'site'  => site_url(),
      'title' => get_the_title($postId),
      'type'  => strip_tags(get_the_term_list($postId, 'elementor_library_type')),
      'json'  => (new Exporter)->export($postId)
    ];

    $url  = $full->getFullDashboardApiUrl() . '-customer/v1/template/cloud';

    $request  = wp_remote_post($url, ['sslverify' => false, 'body' => $payload]);
    $response = wp_remote_retrieve_body($request);
    $response = json_decode($response);

    update_post_meta($postId, 'full_cloud_id', $response->cloud->id);
    update_post_meta($postId, 'full_cloud_slug', $response->cloud->slug);

    return new WP_REST_Response([
      'postId'  => $postId,
      'button'  => '<a href="' . fullGetTemplatesUrl('cloud') . '">Gerenciar</a>'
    ]);
  }

  public function deleteFromCloud(WP_REST_Request $request): WP_REST_Response
  {
    $full   = new FullCustomer();
    $cloudId = (int) $request->get_param('item_id');

    if (!$cloudId) :
      return new WP_REST_Response(['error' => 'Item não localizado no Cloud']);
    endif;

    $payload = [
      'site'  => site_url(),
      'id'    => $cloudId
    ];

    $url  = $full->getFullDashboardApiUrl() . '-customer/v1/template/cloud/';

    $request  = wp_remote_request($url, [
      'method'    => 'delete',
      'sslverify' => false,
      'body'      => $payload
    ]);

    $response = wp_remote_retrieve_body($request);
    $response = json_decode($response);

    if (!$response->success) :
      return new WP_REST_Response(['error' => 'Não foi possível excluir o item do Cloud.']);
    endif;

    global $wpdb;
    $wpdb->delete($wpdb->postmeta, ['meta_key' => 'full_cloud_id', 'meta_value' => $cloudId], ['%s', '%d']);

    return new WP_REST_Response([
      'deleted'  => true,
    ]);
  }

  private function hasElementor(): bool
  {
    return class_exists('Full\Customer\Elementor\TemplateManager');
  }

  private function downloadJson(string $url): ?array
  {
    $request = wp_remote_get($url, ['sslverify' => false]);
    $data    = json_decode(wp_remote_retrieve_body($request), ARRAY_A);

    return $data ? $data : null;
  }
}
