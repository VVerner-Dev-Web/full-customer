<?php

namespace FC\Skills;

use FC\Actions\PluginActivationFactory;
use FC\Actions\PluginActivationManager;
use FC\Actions\PluginReactivate;
use FC\User;

class ActivateProPlugin extends AbstractSkill
{
  const ID = 'activateProPlugin';

  public function getName(): string
  {
    return 'Ativações';
  }

  public function getShortDescription(): string
  {
    return 'Ative plugins PRO no seu site.';
  }

  public function getDescription(): string
  {
    return 'Consulte status, solicite ativação e solicite suporte para os plugins PRO que você possui acesso em seu plano FULL.';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/energy.svg';
  }

  public function getInputPlaceholder(): string
  {
    return 'Selecione quais plugins você quer ativar para começar a automação';
  }

  public function isDefault(): bool
  {
    return User::instance()->isConnected();
  }

  public function isAvailable(): bool
  {
    return User::instance()->isConnected();
  }

  public function isSoon(): bool
  {
    return false;
  }

  public function getFeaturesList(): array
  {
    return [
      'Consultar assentos disponíveis',
      'Solicitar novas ativações',
      'Consultar status de ativações',
      'Recursos PRO disponíveis',
    ];
  }

  public function actions(): array
  {
    $data = fcDashboardAPI('GET', 'plugin-repository/all');
    $plugins = $data['success'] ? $data['data'] : [];
    $actions = [];

    foreach ($plugins as $plugin) {
      if (!isset($plugin['activation']) || $plugin['activation']['id'] === 0) {
        $actions[] = new PluginActivationFactory($plugin);
        continue;
      }

      $actions[] = new PluginActivationManager($plugin);

      if ($plugin['activation']['status'] === 'success') {
        $actions[] = new PluginReactivate($plugin);
      }

      if ($plugin['activation']['status'] === 'expired') {
        // $actions[] = new PluginReactivate($plugin);
      }
    }

    return $actions;
  }
}
