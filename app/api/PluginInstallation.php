<?php

namespace Full\Customer\Api;

use Exception;
use Full\Customer\FileSystem;
use \FullCustomerController;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;
use WP_Error;

defined('ABSPATH') || exit;

class PluginInstallation extends FullCustomerController
{
  private $pluginDir = null;
  private $pluginFile = null;
  private $fileSystem;

  public function __construct()
  {
    parent::__construct();

    $this->fileSystem = new FileSystem();
  }

  public static function registerRoutes(): void
  {
    $api = new self();

    register_rest_route(self::NAMESPACE, '/install-plugin', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'installPlugin'],
        'permission_callback' => 'is_user_logged_in',
      ]
    ]);
  }

  public function installPlugin(WP_REST_Request $request): WP_REST_Response
  {
    $data               = $request->get_json_params();
    $file               = isset($data['file']) ? $data['file'] : null;
    $this->pluginFile   = isset($data['activationFile']) ? $data['activationFile'] : null;

    if (!$file) :
      return new WP_REST_Response(['code' => -1]);
    endif;

    try {
      $tempDir = $this->downloadPlugin($file);

      $this->movePluginFiles($tempDir);

      $activated = $this->activatePlugin();

      if (is_wp_error($activated)) :
        $this->deactivatePlugin();
        throw new Exception($activated->get_error_message());
      endif;

      if (!$this->isSuccessfulActivation()) :
        $this->deactivatePlugin();
        throw new Exception('Houve um erro ao tentar ativar o plugin no site');
      endif;
    } catch (Exception $e) {
      return new WP_REST_Response([
        'code'    => -8,
        'message' => $e->getMessage()
      ]);
    }

    return new WP_REST_Response(['code' => 1]);
  }

  private function downloadPlugin(string $source): string
  {
    $zipFile  = basename($source);
    $unzipDir = get_temp_dir() . uniqid('full-');

    if (!mkdir($unzipDir, 0777, true)) :
      throw new Exception('Não foi possível criar o diretório temporário para extração do zip');
    endif;

    $download = wp_remote_get($source, [
      'sslverify' => false,
      'timeout'   => 60,
      'stream'    => true,
      'filename'  => $zipFile
    ]);

    if (!$download) :
      throw new Exception('Não foi possível fazer o download do zip do plugin');
    endif;

    $fs = new FileSystem;
    $fs->extractZip($zipFile, $unzipDir, false);

    $scan = scandir($unzipDir);
    $scan = $scan ? array_diff($scan, ['.', '..', '__MACOSX']) : [];

    $this->pluginDir = array_pop($scan);

    if (!$this->pluginDir) :
      throw new Exception('Não foi possível definir o diretório de trabalho do plugin');
    endif;

    return $unzipDir . DIRECTORY_SEPARATOR . $this->pluginDir;
  }

  private function movePluginFiles(string $origin)
  {
    $moved = $this->fileSystem->moveFile($origin, $this->getPluginActivationDir());

    if (!$moved) :
      throw new Exception('Não foi possível mover os arquivos do plugin para o diretório do WordPress');
    endif;
  }

  private function activatePlugin(): ?WP_Error
  {
    if (!function_exists('activate_plugin')) :
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    endif;

    $completePluginPath = $this->getPluginActivationDir() . '/' . $this->pluginFile;

    ob_start();
    plugin_sandbox_scrape(plugin_basename($completePluginPath));

    if (ob_get_length() > 0) :
      $output = ob_get_clean();
      return new WP_Error('unexpected_output', __('The plugin generated unexpected output.'), $output);
    endif;

    return activate_plugin($completePluginPath);
  }

  private function deactivatePlugin(): void
  {
    if (!function_exists('deactivate_plugins')) :
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    endif;

    $completePluginPath = $this->getPluginActivationDir() . '/' . $this->pluginFile;
    deactivate_plugins($completePluginPath, true);
  }

  private function getPluginActivationDir(): string
  {
    return WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->pluginDir;
  }

  private function isSuccessfulActivation(): bool
  {
    $test       = wp_remote_get(home_url(), ['sslverify' => false]);
    $statusCode = (int) wp_remote_retrieve_response_code($test);

    return $statusCode === 200 || $statusCode === 201;
  }
}
