<?php

namespace Full\Customer\Api;

use Exception;
use \FullCustomerController;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

defined('ABSPATH') || exit;

class PluginInstallation extends FullCustomerController
{
  public static function registerRoutes(): void
  {
    $api = new self();

    register_rest_route(self::NAMESPACE, '/install-plugin', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'installPlugin'],
        'permission_callback' => [$api, 'permissionCallback'],
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/activate-elementor-pro', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'activateElementorPro'],
        'permission_callback' => [$api, 'permissionCallback'],
      ]
    ]);
  }

  public function activateElementorPro(WP_REST_Request $request): WP_REST_Response
  {
    try {
      $licenseKey = $request->get_param('licenseKey');

      if (!file_exists(WP_PLUGIN_DIR . '/elementor-pro/license/admin.php')) {
        throw new Exception('O plugin Elementor PRO precisa estar instalado em seu site para funcionar');
      }

      if (!class_exists('\ElementorPro\License\Admin')) {
        require_once WP_PLUGIN_DIR . '/elementor-pro/license/admin.php';
      }

      if (!class_exists('\ElementorPro\License\API')) {
        require_once WP_PLUGIN_DIR . '/elementor-pro/license/api.php';
      }

      $data = \ElementorPro\License\API::activate_license($licenseKey);

      if (!isset($data['success']) || $data['success'] !== true) {
        throw new Exception('Não foi possível ativar a licença do Elementor PRO.');
      }

      update_option('elementor_pro_license_key', $licenseKey);
      \ElementorPro\License\API::set_license_data($data);
    } catch (\Exception $e) {
      return rest_ensure_response([
        'success' => false,
        'data' => $e->getMessage(),
        'code' => -66
      ]);
    }

    return rest_ensure_response([
      'success' => true,
      'data' => 'Elementor PRO instalado e ativado com sucesso! Aproveite as funcionalidades PRO!'
    ]);
  }

  public function installPlugin(): WP_REST_Response
  {
    return new WP_REST_Response([
      'code'    => -100,
      'message' => 'Instalação indisponível'
    ]);
  }
}
