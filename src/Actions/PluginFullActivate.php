<?php

namespace FC\Actions;

use WP_REST_Request;
use WP_REST_Response;

class PluginFullActivate extends AbstractAction
{
  public function getIcon(): string
  {
    return '';
  }

  public function getName(): string
  {
    return 'Solicitar ativação do plugin na FULL.';
  }

  public function getShortDescription(): string
  {
    return 'Permite o usuário solicitar a ativação de um plugin na FULL para consumo de licença.';
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
    return 'actions/plugins/full-activate/(?P<processId>[a-zA-Z0-9-]+)';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $slug = $request->get_param('pluginSlug');
    $activate = fcDashboardAPI('POST', 'plugin-repository/' . $slug . '/activate');

    if (!$activate['success']) {
      return new WP_REST_Response([
        'success' => $activate['success'],
        'error' => isset($activate['message']) && $activate['message'] ? $activate['message'] : '',
      ]);
    }

    return new WP_REST_Response([
      'success' => true,
      'message' => $activate['data']['message']
    ]);
  }
}
