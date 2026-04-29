<?php

namespace FC\Actions;

use FC\FileSystem;
use FC\PluginRepository as FCPluginRepository;
use WP_REST_Request;
use WP_REST_Response;

class PluginInstall extends AbstractAction
{
  public function getIcon(): string
  {
    return '';
  }

  public function getName(): string
  {
    return 'Instalar plugin';
  }

  public function getShortDescription(): string
  {
    return 'Permite o usuário instalar os plugins que ele tem pela FULL.';
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), []);
  }

  public function getRestMethod(): string
  {
    return 'POST';
  }

  public function getRestRoute(): string
  {
    return 'actions/plugins/install/(?P<processId>[a-zA-Z0-9-]+)';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';

    $fs = FileSystem::instance();
    $pid = $request->get_param('processId');

    $plugins = (new FCPluginRepository())->getPlugins();
    $plugin = array_filter($plugins, fn($plugin) => $plugin['plugin'] === $request->get_param('plugin'));
    $plugin = array_shift($plugin);

    if (!$plugin) {
      return new WP_REST_Response([
        'success' => false,
        'error' => 'Plugin não localizado para instalação'
      ]);
    }

    if (is_plugin_active($plugin['plugin'])) {
      return new WP_REST_Response([
        'success' => true,
        'error' => 'Plugin ja ativado, podemos continuar rapidamente'
      ]);
    }

    ExecutionStatus::updateState($pid, 'Verificando dependências...');

    foreach ($plugin['dependencies'] as $dep) {
      $request = new WP_REST_Request('POST', '/wp/v2/plugins');
      $request->set_param('slug', $dep);
      $request->set_param('status', 'active');
      $request->set_param('context', 'edit');

      ExecutionStatus::updateState($pid, 'Instalando dependência ' . $dep . '...');

      rest_do_request($request);
    }

    ExecutionStatus::updateState($pid, 'Dependências verificadas, iniciando processo de instalação principal...');

    $recoveryLink = ' <a href="' . $plugin['package'] . '">Baixar plugin manualmente</a> ';

    ExecutionStatus::updateState($pid, 'Baixando arquivo do plugin...');

    $package = download_url($plugin['package'], 300);
    if (is_wp_error($package)) {
      return new WP_REST_Response([
        'success' => false,
        'error' => 'Houve um erro ao baixar o arquivo do plugin. ' . $package->get_error_message() . ' ' . $recoveryLink
      ]);
    }

    ExecutionStatus::updateState($pid, 'Download completo');

    $workingDir = $fs->wpContentDir() . 'upgrade/' . $plugin['slug'];

    if ($fs->isDir($workingDir)) {
      $fs->delete($workingDir, true);
    }

    wp_mkdir_p($workingDir);
    $done = unzip_file($package, $workingDir);

    if (is_wp_error($done)) {
      return new WP_REST_Response([
        'success' => false,
        'error' => 'Houve um erro ao descompactar o arquivo do plugin. ' . $done->get_error_message() . ' ' . $recoveryLink
      ]);
    }

    ExecutionStatus::updateState($pid, 'Arquivo descompactado');

    $fs->delete($package);

    $done = copy_dir($workingDir, WP_PLUGIN_DIR);
    if (is_wp_error($done)) {
      return new WP_REST_Response([
        'success' => false,
        'error' => 'Houve um erro ao copiar o arquivo do plugin. ' . $done->get_error_message() . ' ' . $recoveryLink
      ]);
    }

    $fs->delete($workingDir, true);

    $pluginActivationPath = trailingslashit(WP_PLUGIN_DIR) . $plugin['plugin'];
    ExecutionStatus::updateState($pid, 'Arquivo transferido. Solicitando ativação do plugin no WordPress');

    if (!is_plugin_active($pluginActivationPath)) {
      activate_plugin($pluginActivationPath);
    }

    ExecutionStatus::deleteState($pid);

    return new WP_REST_Response([
      'success' => true,
      'message' => 'Plugin instalado com sucesso!'
    ]);
  }
}
