<?php

namespace FC\Actions;

use WP_REST_Request;
use WP_REST_Response;

class PluginActivate extends AbstractAction
{
  public function getIcon(): string
  {
    return '';
  }

  public function getName(): string
  {
    return 'Ativar plugin';
  }

  public function getShortDescription(): string
  {
    return 'Permite o usuário ativar os plugins que ele tem pela FULL.';
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
    return 'actions/plugins/activate/(?P<processId>[a-zA-Z0-9-]+)';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $pid = $request->get_param('processId');
    $plugin = $request->get_param('plugin');

    ExecutionStatus::updateState($pid, 'Verificando ativação do plugin no WordPress');

    if (!is_plugin_active($plugin)) {
      activate_plugin($plugin);
    }

    ExecutionStatus::deleteState($pid);

    do_action('fc/updates/invalidate');

    return new WP_REST_Response([]);
  }
}
