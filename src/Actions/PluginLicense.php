<?php

namespace FC\Actions;

use FC\PluginRepository as FCPluginRepository;
use WP_REST_Request;
use WP_REST_Response;

class PluginLicense extends AbstractAction
{
  public function getIcon(): string
  {
    return '';
  }

  public function getName(): string
  {
    return 'Ativar licença no plugin';
  }

  public function getShortDescription(): string
  {
    return 'Permite o usuário solicitar a ativação da licença PRO nos plugins que ele tem pela FULL.';
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
    return 'actions/plugins/license/(?P<processId>[a-zA-Z0-9-]+)';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $pid = $request->get_param('processId');

    $plugins = (new FCPluginRepository())->getPlugins();
    $plugin = array_filter($plugins, fn($plugin) => $plugin['plugin'] === $request->get_param('plugin'));
    $plugin = array_shift($plugin);

    if (!$plugin) {
      return new WP_REST_Response([
        'success' => false,
        'error' => 'Plugin não localizado para licença'
      ]);
    }

    ExecutionStatus::updateState($pid, 'Iniciando processo de ativação da licença do plugin');

    $activate = fcDashboardAPI('POST', 'plugin-repository/' . $plugin['slug'] . '/activate', [
      'cookies' => $request->get_param('authorizationCookies'),
    ]);

    ExecutionStatus::deleteState($pid);

    return new WP_REST_Response([
      'success' => $activate['success'],
      'message' => isset($activate['data']) && $activate['data'] ? $activate['data'] : '',
      'error'   => isset($activate['error']) && $activate['error'] ? $activate['error'] : ''
    ]);
  }
}
