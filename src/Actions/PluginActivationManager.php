<?php

namespace FC\Actions;

use WP_REST_Request;
use WP_REST_Response;

class PluginActivationManager extends AbstractAction
{
  private array $repoPlugin;

  public function __construct(array $repoPlugin)
  {
    $this->repoPlugin = $repoPlugin;
  }

  public function getIcon(): string
  {
    return $this->repoPlugin['image_url'];
  }

  public function getName(): string
  {
    return $this->repoPlugin['name'];
  }

  public function getShortDescription(): string
  {
    return $this->repoPlugin['sections']['description'];
  }

  public function isAvailable(): bool
  {
    if (isset($this->repoPlugin['activation']) && is_array($this->repoPlugin['activation'])) {
      return intval($this->repoPlugin['activation']['id']) !== 0;
    }

    return false;
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), [
      'id' => $this->repoPlugin['id'],
      'imageUrl' => $this->repoPlugin['image_url'],
      'name' => $this->getName(),
      'desc' => 'Consultar, reativar ou renovar ativação',
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
    return 'actions/activation/' . $this->repoPlugin['slug'];
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $msg = 'No dia %s foi solicitada a ativação do plugin %s por %s - %s (FULL #%s). <br><br> Atualmente a licença está com <strong>status %s</strong>';
    $actions = [];

    $replace = [
      date_i18n('d/m/Y \à\s H:i', strtotime($this->repoPlugin['activation']['created_date'])),
      $this->repoPlugin['name'],
      $this->repoPlugin['activation']['requester_name'],
      $this->repoPlugin['activation']['requester_email'],
      $this->repoPlugin['activation']['requester_id'],
      $this->repoPlugin['activation']['status_label']
    ];

    if ($this->repoPlugin['activation']['status'] === 'success') {
      $msg .= " e foi marcada como concluída em %s.";
      $replace[] = date_i18n('d/m/Y \à\s H:i', strtotime($this->repoPlugin['activation']['completed_date']));

      $actions[] = [
        'label' => 'Reativar',
        'action' => 'reactivate.' . $this->repoPlugin['id'],
      ];
    }

    if ($this->repoPlugin['activation']['status'] === 'expired') {
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
