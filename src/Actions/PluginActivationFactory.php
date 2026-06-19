<?php

namespace FC\Actions;

use WP_REST_Request;
use WP_REST_Response;

class PluginActivationFactory extends AbstractAction
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
      return intval($this->repoPlugin['activation']['id']) === 0;
    }

    return false;
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), [
      'id' => $this->repoPlugin['id'],
      'imageUrl' => $this->repoPlugin['image_url'],
      'name' => $this->getName(),
      'desc' => 'Solicitar nova ativação',
      'simpleRest' => false,
      'extraProps' => [
        'plugin' => $this->repoPlugin['plugin'],
        'pluginSlug' => $this->repoPlugin['slug']
      ]
    ]);
  }

  public function inShellActions(): array
  {
    return [
      PluginFullActivate::class,
      PluginInstall::class,
      PluginWordPressActivate::class,
      PluginLicense::class,
      ExecutionStatus::class
    ];
  }

  public function getRestMethod(): string
  {
    return 'POST';
  }

  public function getRestRoute(): string
  {
    // FYI: this action is a shell for other actions so we don't register a rest route
    return '';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    return new WP_REST_Response([]);
  }

  public function getCta(): string
  {
    $cta = 'Ativar';

    $balance = $this->repoPlugin['balance'];

    if (!is_array($balance) || 0 >= $balance['available']) {
      $cta = 'Comprar';
    }

    if ($this->repoPlugin['activation']['id']) {
      $cta = 'Consultar';
    }

    return $cta;
  }
}
