<?php

namespace Full\Customer\Api;

use \FULL_CUSTOMER_Controller;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

defined('ABSPATH') || exit;

class Plugin extends FULL_CUSTOMER_Controller
{
  private const TEMPORARY_DIR = WP_CONTENT_DIR . '/full-temp';

  private $pluginDir = null;
  private $pluginFile = null;

  public function __construct()
  {
    parent::__construct();
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
    if (!function_exists('activate_plugin')) :
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    endif;

    $data               = $request->get_json_params();
    $file               = isset($data['file']) ? $data['file'] : null;
    $this->pluginFile   = isset($data['activationFile']) ? $data['activationFile'] : null;

    if (!$file) :
      return new WP_REST_Response(['code' => -1]);
    endif;

    $this->createTemporaryDir();

    $copied = $this->copyZipFile($file);
    if (!$copied) :
      $this->removeTemporaryDir();
      return new WP_REST_Response(['code' => -2]);
    endif;

    $this->setPluginDir();

    $moved   = $this->movePluginFiles();
    if (!$moved) :
      $this->removeTemporaryDir();
      return new WP_REST_Response(['code' => -3]);
    endif;

    $activated = $this->activatePlugin();
    if (is_wp_error($activated)) :
      $this->deactivatePlugin();
      $this->removeTemporaryDir();
      return new WP_REST_Response([
        'code'      => -4,
        'message'   => $activated->get_error_message()
      ]);
    endif;

    if (!$this->isSuccessfulActivation()) :
      $this->deactivatePlugin();
      $this->removeTemporaryDir();
      return new WP_REST_Response(['code' => -5]);
    endif;

    $this->removeTemporaryDir();

    return new WP_REST_Response(['code' => 1]);
  }

  private function copyZipFile(string $source): bool
  {
    global $wp_filesystem;

    if (!$wp_filesystem) :
      require_once ABSPATH . '/wp-admin/includes/file.php';
      WP_Filesystem();
    endif;

    $pluginRequest = wp_remote_get($source, ['sslverify' => false]);
    $zipContent    = wp_remote_retrieve_body($pluginRequest);
    $zipPath       = self::TEMPORARY_DIR . '/plugin.zip';

    $wp_filesystem->put_contents($zipPath, $zipContent);

    $worker = new ZipArchive;
    $res    = $worker->open($zipPath);

    if ($res !== true) :
      unlink($zipPath);
      return false;
    endif;

    $worker->extractTo(self::TEMPORARY_DIR);
    $worker->close();

    unlink($zipPath);

    return true;
  }

  private function movePluginFiles(): bool
  {
    if (!$this->pluginDir) :
      return false;
    endif;

    if (is_dir($this->getPluginActivationDir())) :
      $this->removeDirCompletely($this->getPluginActivationDir());
    endif;

    return rename(
      self::TEMPORARY_DIR . '/' . $this->pluginDir,
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

  private function removeTemporaryDir(): void
  {
    $this->removeDirCompletely(self::TEMPORARY_DIR);
  }

  private function createTemporaryDir(): void
  {
    if (!is_dir(self::TEMPORARY_DIR)) :
      mkdir(self::TEMPORARY_DIR);
    endif;
  }

  private function getPluginActivationDir(): string
  {
    return WP_PLUGIN_DIR . '/' . $this->pluginDir;
  }

  private function removeDirCompletely(string $path): void
  {
    $files = glob($path . '/*');

    foreach ($files as $file) :
      is_dir($file) ? $this->removeDirCompletely($file) : unlink($file);
    endforeach;

    rmdir($path);
  }

  private function isSuccessfulActivation(): bool
  {
    $test       = wp_remote_get(home_url(), ['sslverify' => false]);
    $statusCode = (int) wp_remote_retrieve_response_code($test);

    return $statusCode === 200;
  }
}
