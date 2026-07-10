<?php

namespace FC\Actions;

use WP_REST_Request;
use WP_REST_Response;

class PluginActivationManager extends AbstractAction
{
  private ?array $repoPlugin = null;

  public function __construct(?array $repoPlugin = null)
  {
    $this->repoPlugin = $repoPlugin;
  }

  public function getIcon(): string
  {
    return $this->repoPlugin && isset($this->repoPlugin['image_url']) ? $this->repoPlugin['image_url'] : '';
  }

  public function getName(): string
  {
    return 'Consultar ativação';
  }

  public function getShortDescription(): string
  {
    return 'Consultar o status atual e histórico da ativação deste plugin.';
  }

  public function isAvailable(): bool
  {
    if ($this->repoPlugin && isset($this->repoPlugin['activation']) && is_array($this->repoPlugin['activation'])) {
      return intval($this->repoPlugin['activation']['id']) !== 0;
    }

    return false;
  }

  public function getPromptArgs(): array
  {
    if (!$this->repoPlugin) {
      return $this->_defaultPromptArgs();
    }

    return array_merge($this->_defaultPromptArgs(), [
      'id' => $this->repoPlugin['id'],
      'imageUrl' => $this->repoPlugin['image_url'],
      'name' => $this->getName(),
      'desc' => $this->getShortDescription(),
      'simpleRest' => true,
      'extraProps' => [
        'plugin' => $this->repoPlugin['plugin']
      ]
    ]);
  }

  public function getRestMethod(): string
  {
    return 'GET';
  }

  public function getRestRoute(): string
  {
    $slug = $this->repoPlugin && isset($this->repoPlugin['slug']) ? $this->repoPlugin['slug'] : '(?P<pluginSlug>[a-zA-Z0-9-]+)';
    return 'actions/activation/' . $slug;
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $repoPlugin = $this->repoPlugin;

    if (!$repoPlugin) {
      $slug = $request->get_param('pluginSlug');
      $data = fcDashboardAPI('GET', 'plugin-repository/' . $slug . '/info');
      $repoPlugin = $data['success'] ? $data['data'] : [];
    }

    if (!$repoPlugin || !isset($repoPlugin['activation'])) {
      return new WP_REST_Response([
        'success' => false,
        'error' => 'Plugin não localizado no repositório.'
      ], 404);
    }

    $msg = 'No dia %s foi solicitada a ativação do plugin %s por %s - %s (FULL #%s). <br><br> Atualmente a licença está com <strong>status %s</strong>';
    $actions = [];

    $replace = [
      date_i18n('d/m/Y \à\s H:i', strtotime($repoPlugin['activation']['created_date'])),
      $repoPlugin['name'],
      $repoPlugin['activation']['requester_name'],
      $repoPlugin['activation']['requester_email'],
      $repoPlugin['activation']['requester_id'],
      $repoPlugin['activation']['status_label']
    ];

    if ($repoPlugin['activation']['status'] === 'success') {
      $msg .= " e foi marcada como concluída em %s.";
      $replace[] = date_i18n('d/m/Y \à\s H:i', strtotime($repoPlugin['activation']['completed_date']));

      $actions[] = [
        'label' => 'Reativar',
        'action' => 'reactivate.' . $repoPlugin['id'],
      ];
    }

    if ($repoPlugin['activation']['status'] === 'expired') {
      $actions[] = [
        'label' => 'Renovar',
        'action' => 'extend',
      ];
    }

    $actions[] = [
      'label' => 'Solicitar ajuda',
      'action' => 'help',
    ];

    return new WP_REST_Response([
      'success' => true,
      'message' => sprintf($msg, ...$replace),
      'actions' => $actions
    ]);
  }
}
