<?php

namespace FC\Models;

use FC\Agents\AgentFactory;
use FC\User;

class PluginsModel extends AbstractModel
{
  const ID = 'activateProPlugin';

  public function getId(): string
  {
    return self::ID;
  }

  public function getName(): string
  {
    return 'Plugins';
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

  public function agents(): array
  {
    $agents = [];
    $data = fcDashboardAPI('GET', 'plugin-repository/all');
    $plugins = $data['success'] ? $data['data'] : [];

    foreach ($plugins as $plugin) {
      if (strpos($plugin['plugin'], 'full-customer') !== false) {
        continue;
      }

      $agents[] = AgentFactory::create($plugin);
    }

    return $agents;
  }
}
