<?php

namespace Full\Customer\Api;

use Full\Customer\FileSystem;
use \FullCustomerController;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;
use ZipArchive;
use WP_Error;

defined('ABSPATH') || exit;

class Plugin extends FullCustomerController
{
  private const TEMPORARY_DIR = WP_CONTENT_DIR . '/full-temporary';

  private $pluginDir = null;
  private $pluginFile = null;
  private $fs;

  public function __construct()
  {
    parent::__construct();

    $this->fs = new FileSystem();
  }

  public static function registerRoutes(): void
  {
    $api = new self();

    register_rest_route(self::NAMESPACE, '/install-plugin', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'installPlugin'],
        // 'permission_callback' => 'is_user_logged_in',
        'permission_callback' => '__return_true',
      ]
    ]);
  }

  public function installPlugin(WP_REST_Request $request): WP_REST_Response
  {
    if (!function_exists('activate_plugin')) :
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    endif;

    $data               = $request->get_json_params();
    $file               = isset($data['file']) ? $data['file'] : null;
    $this->pluginFile   = isset($data['activationFile']) ? $data['activationFile'] : null;

    error_log(json_encode($data, true));

    if (!$file) :
      return new WP_REST_Response(['code' => -1]);
    endif;

    $this->fs->createTemporaryDirectory();

    $copied = $this->copyZipFile($file);
    if (!$copied) :
      $this->fs->deleteTemporaryDirectory();
      return new WP_REST_Response(['code' => -2]);
    endif;

    $this->setPluginDir();

    $moved   = $this->movePluginFiles();
    if (!$moved) :
      $this->fs->deleteTemporaryDirectory();
      return new WP_REST_Response(['code' => -3]);
    endif;

    $activated = $this->activatePlugin();
    if (is_wp_error($activated)) :
      $this->deactivatePlugin();
      $this->fs->deleteTemporaryDirectory();
      return new WP_REST_Response([
        'code'      => -4,
        'message'   => $activated->get_error_message()
      ]);
    endif;

    if (!$this->isSuccessfulActivation()) :
      $this->deactivatePlugin();
      $this->fs->deleteTemporaryDirectory();
      return new WP_REST_Response(['code' => -5]);
    endif;

    $this->fs->deleteTemporaryDirectory();

    return new WP_REST_Response(['code' => 1]);
  }

  private function copyZipFile(string $source): bool
  {
    $zip  = $this->downloadPluginZip($source);
    return $this->fs->extractZip($zip, $this->fs->getTemporaryDirectoryPath());
  }

  private function downloadPluginZip(string $source): string
  {
    $path = $this->fs->getTemporaryDirectoryPath() . DIRECTORY_SEPARATOR . 'plugin.zip';

    if (!file_exists($path)) :
      $file = fopen($path, 'a');
      fclose($file);
    endif;

    wp_remote_get($source, [
      'sslverify' => false,
      'stream'    => true,
      'filename'  => $path
    ]);

    return $path;
  }

  private function movePluginFiles(): bool
  {
    error_log($this->pluginDir);

    if (!$this->pluginDir) :
      return false;
    endif;

    if (is_dir($this->getPluginActivationDir())) :
      error_log('é dir');
      $this->fs->deleteDirectory($this->getPluginActivationDir(), true);
    endif;

    return rename(
      self::TEMPORARY_DIR . DIRECTORY_SEPARATOR . $this->pluginDir,
      $this->getPluginActivationDir()
    );
  }

  private function setPluginDir(): void
  {
    $scan = scandir(self::TEMPORARY_DIR);
    $scan = array_diff($scan, ['.', '..', '__MACOSX']);
    $this->pluginDir = array_pop($scan);
  }

  private function activatePlugin(): ?WP_Error
  {
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

    return $statusCode === 200;
  }
}
