<?php

namespace FC\Actions;

use FC\Services\LocalLicenseProcessor;
use FC\User;
use WP_REST_Request;
use WP_REST_Response;

class PluginReactivate extends AbstractAction
{
  private array $repoPlugin;

  public function __construct(array $repoPlugin)
  {
    $this->repoPlugin = $repoPlugin;
  }

  public function getIcon(): string
  {
    return '';
  }

  public function getName(): string
  {
    return 'Reativar ' . $this->repoPlugin['name'];
  }

  public function getShortDescription(): string
  {
    return 'Permite o usuário reative os plugins que ele já ativou anteriormente pela FULL.';
  }

  public function showInActionsDropdown(): bool
  {
    return false;
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), [
      'id' => 'reactivate.' . $this->repoPlugin['id'],
      'name' => $this->getName(),
      'desc' => 'Reativar licença',
      'simpleRest' => true,
      'extraProps' => [
        'plugin' => $this->repoPlugin['plugin']
      ]
    ]);
  }

  public function getRestMethod(): string
  {
    return 'POST';
  }

  public function getRestRoute(): string
  {
    return 'actions/activation/' . $this->repoPlugin['slug'] . '/reactivate';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $slug = $this->repoPlugin['slug'];
    $data = fcDashboardAPI('POST', 'plugin-repository/' . $slug . '/reactivate');

    if (!$data['success']) {
      return new WP_REST_Response([
        'success' => false,
        'error' => $data['message']
      ]);
    }

    $data = fcDashboardAPI('POST', 'plugin-repository/' . $slug . '/license');

    $processor = new LocalLicenseProcessor();
    $result = $processor->process($slug, $data['data']['license'] ?? '');

    if ($result['success']) {
      fcDashboardAPI('POST', 'plugin-repository/' . $slug . '/license/confirm');
    }

    do_action('fc/updates/invalidate');

    return new WP_REST_Response([
      'success' => true,
      'message' => $result['success'] ? 'Plugin reativado com sucesso e pronto para uso! Aproveite.' : 'A ativação automática falhou, nossa equipe técnica já foi acionada para solucionar o caso.',
      'result' => $result
    ]);
  }
}
