<?php

namespace FC\Actions;

use FC\Services\LocalLicenseProcessor;
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
    $slug = $request->get_param('pluginSlug');

    $data = fcDashboardAPI('POST', 'plugin-repository/' . $slug . '/license');

    if (!$data['success']) {
      return new WP_REST_Response([
        'success' => false,
        'error' => $data['message']
      ]);
    }

    $processor = new LocalLicenseProcessor();
    $result = $processor->process($slug, $data['data']['license'] ?? '');

    if ($result['success']) {
      fcDashboardAPI('POST', 'plugin-repository/' . $slug . '/license/confirm');
    }

    do_action('fc/updates/invalidate');

    return new WP_REST_Response([
      'success' => true,
      'message' => $result['success'] ? 'Plugin ativado com sucesso e pronto para uso! Aproveite.' : 'A ativação automática falhou, nossa equipe técnica já foi acionada para solucionar o caso.',
      'result' => $result
    ]);
  }
}
