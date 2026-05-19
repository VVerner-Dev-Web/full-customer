<?php

namespace FC\Skills;

use FC\Actions\PluginActivationFactory;
use FC\Actions\PluginActivationManager;
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

    return array_map(
      fn($plugin) =>
      isset($plugin['activation']) && $plugin['activation']['id'] > 0 ? new PluginActivationManager($plugin) : new PluginActivationFactory($plugin),
      $plugins
    );
  }
}
