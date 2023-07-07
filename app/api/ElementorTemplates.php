<?php

namespace Full\Customer\Api;

use Full\Customer\Elementor\Exporter;
use Full\Customer\Elementor\Importer;
use Full\Customer\Elementor\TemplateManager;
use Full\Customer\FileSystem;
use FullCustomer;
use \FullCustomerController;
use stdClass;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

defined('ABSPATH') || exit;

class ElementorTemplates extends FullCustomerController
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

    register_rest_route(self::NAMESPACE, '/elementor/install-dependencies', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'installDependencies'],
        'permission_callback' => [$api, 'permissionCallback'],
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/elementor/download', [
      [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => [$api, 'download'],
        'permission_callback' => '__return_true',
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

  public function download(WP_REST_Request $request): void
  {
    $itemId = (int) $request->get_param('id');

    header("Content-type: application/vnd.ms-excel");
    header("Content-Type: application/force-download");
    header("Content-Type: application/download");
    header("Content-disposition: teste.json");
    header("Content-disposition: filename=teste.json");

    $item     = TemplateManager::instance()->getCloudItem($itemId);
    $template = $this->downloadJson($item->fileUrl);

    exit(fullJsonEncode($template));
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

  public function installDependencies(WP_REST_Request $request): WP_REST_Response
  {
    $item   = $request->get_param('item');
    $itemId = (int) $item['id'];

    $item     = TemplateManager::instance()->getItem($itemId);

    if (!$item || !$item->canBeInstalled) :
      return new WP_REST_Response(['error' => 'O item selecionado não pode ser instalado.']);
    endif;

    $dependencies = $this->verifyMissingDependencies($item);

    if (!$dependencies) :
      return new WP_REST_Response(['message' => 'Nenhuma dependência pendente localizada']);
    endif;

    if (!function_exists('activate_plugin')) :
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    endif;

    foreach ($dependencies['uninstalled'] as $dep) :
      $request = new WP_REST_Request('POST', '/wp/v2/plugins');
      $request->set_param('slug', $dep->slug);
      $request->set_param('status', 'active');
      $request->set_param('context', 'edit');

      rest_do_request($request);
    endforeach;

    foreach ($dependencies['inactive'] as $dep) :
      activate_plugin($dep->localPath);
    endforeach;

    return new WP_REST_Response(['message' => 'Dependências instaladas! Vamos importar o template escolhi agora.']);
  }

  private function installTemplate(int $itemId, string $mode): WP_REST_Response
  {
    $item     = TemplateManager::instance()->getItem($itemId);

    if (!$item || !$item->canBeInstalled) :
      return new WP_REST_Response(['error' => 'O item selecionado não pode ser instalado.']);
    endif;

    $dependencies = $this->verifyMissingDependencies($item);

    if ($dependencies) :
      ob_start();

      require_once FULL_CUSTOMER_APP . '/views/components/template-missing-dependencies.php';

      return new WP_REST_Response([
        'dependencies'  => ob_get_clean(),
        'mode'          => $mode
      ]);
    endif;

    if ($item->hasZipFile) :
      return $this->installPack($item, $mode);
    endif;

    $importer = new Importer;
    $template = $this->downloadJson($item->fileUrl);

    if (!$template) :
      return new WP_REST_Response(['error' => 'O item selecionado não foi localizado.']);
    endif;

    $data     = $importer->get_data($template);

    if ('builder' === $mode) :
      return new WP_REST_Response(['builder' => $data]);
    endif;

    $template['page_title']  = $item->title;
    $template['title']  = $item->title;

    if (!isset($template['type'])) :
      $template['type']  = 'page';
    endif;

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
      'visitUrl'  => get_permalink($postId),
      'message'   => 'Template importado com sucesso!'
    ]);
  }

  private function installPack(stdClass $item, string $mode): WP_REST_Response
  {
    $templates = $this->downloadJsonPack($item->fileUrl);
    $importer  = new Importer;
    $postsIds  = [];

    if (!$templates) :
      return new WP_REST_Response([
        'error' => 'Não foi possível fazer o download do pack',
      ]);
    endif;

    foreach ($templates as $template) :
      $json = json_decode(file_get_contents($template), ARRAY_A);
      $data = $importer->get_data($json);

      $postId = ('page' === $mode) ?
        $importer->create_page($data) :
        $importer->import_in_library($data);

      $postsIds[] = $postId;
    endforeach;

    return new WP_REST_Response([
      'message'  => 'Pack importado com sucesso!',
    ]);
  }

  private function installCloud(int $itemId, string $mode): WP_REST_Response
  {
    $importer = new Importer;
    $item     = TemplateManager::instance()->getCloudItem($itemId);
    $template = $this->downloadJson($item->fileUrl);

    if (!$template) :
      return new WP_REST_Response(['error' => 'O item selecionado não foi localizado.']);
    endif;

    $data     = $importer->get_data($template);

    if ('builder' === $mode) :
      return new WP_REST_Response(['builder' => $data]);
    endif;

    $template['page_title']  = $item->title;
    $template['title']  = $item->title;

    if (!isset($template['type'])) :
      $template['type']  = 'page';
    endif;

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
      'visitUrl'  => get_permalink($postId),
      'message'   => 'Template cloud importado com sucesso!'
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
      'json'  => fullJsonEncode(compact('type', 'content'))
    ];

    $url  = $full->getFullDashboardApiUrl() . '-customer/v1/template/cloud';

    $request  = wp_remote_post($url, ['sslverify' => false, 'body' => $payload]);
    $response = wp_remote_retrieve_body($request);
    $response = json_decode($response);

    return new WP_REST_Response($response);
  }

  public function sendToCloud(WP_REST_Request $request): WP_REST_Response
  {
    $full     = new FullCustomer();
    $postId   = (int) $request->get_param('post_id');
    $worker   = new Exporter();
    $payload  = [
      'site'  => site_url(),
      'title' => get_the_title($postId),
      'type'  => $worker->get_template_type($postId),
      'json'  => $worker->export($postId)
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

  private function downloadJsonPack(string $url): array
  {
    $zipFile  = uniqid('pack-') . '.zip';
    $unzipDir = get_temp_dir() . str_replace('.zip', '', $zipFile);

    mkdir($unzipDir, 0777, true);

    $download = wp_remote_get($url, [
      'sslverify' => false,
      'timeout'   => 30,
      'stream'    => true,
      'filename'  => $zipFile
    ]);

    if (is_wp_error($download)) :
      return [];
    endif;

    $fs = new FileSystem;
    $fs->extractZip(
      $zipFile,
      $unzipDir
    );

    return is_dir($unzipDir . DIRECTORY_SEPARATOR . 'templates') ? $fs->scanDir($unzipDir . DIRECTORY_SEPARATOR . 'templates') : [];
  }

  private function verifyMissingDependencies(stdClass $item): ?array
  {
    if (!$item->dependencies) :
      return null;
    endif;

    $inactive     = [];
    $uninstalled  = [];

    if (!function_exists('get_plugins')) :
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    endif;

    $activePlugins = (array) get_option('active_plugins', []);
    $plugins       = array_keys(get_plugins());

    $activePlugins = array_map([$this, 'getPluginSlug'], $activePlugins);
    $pluginsSlugs = array_map([$this, 'getPluginSlug'], $plugins);


    foreach ($item->dependencies as $dependency) :
      if (!in_array($dependency->slug, $pluginsSlugs)) :
        $uninstalled[] = $dependency;

      elseif (!in_array($dependency->slug, $activePlugins)) :
        $key = array_search($dependency->slug, $pluginsSlugs);
        $dependency->localPath = $plugins[$key];

        $inactive[] = $dependency;
      endif;
    endforeach;

    return $inactive || $uninstalled ? compact('inactive', 'uninstalled') : null;
  }

  private function getPluginSlug(string $plugin): string
  {
    $slug = explode('/', $plugin);
    return array_shift($slug);
  }
}