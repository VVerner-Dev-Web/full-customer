<?php

namespace FC\Actions;

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
    $remote = fcDashboardAPI('POST', 'plugin-repository/' . $this->repoPlugin['slug'] . '/reactivate', [
      'cookies' => User::instance()->getCurrentCookies()
    ]);

    return new WP_REST_Response([
      'success' => $remote['success'],
      'error' => isset($remote['message']) && $remote['message'] ? $remote['message'] : '',
    ]);
  }
}
