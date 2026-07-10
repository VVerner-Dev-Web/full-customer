<?php

namespace FC\Models;

use FC\Agents\ConnectorAgent;
use FC\User;

class ConnectModel extends AbstractModel
{
  const ID = 'connect';

  public function getId(): string
  {
    return self::ID;
  }

  public function getName(): string
  {
    $connected = User::instance()->isConnected();
    $html = 'Conta FULL. <span class="badge %s">%s</span>';
    return sprintf(
      $html,
      $connected ? 'bg-primary bg-opacity-75' : 'bg-danger bg-opacity-75',
      $connected ? 'Conectado' : 'Desconectado'
    );
  }

  public function getShortDescription(): string
  {
    return 'Modelos para conexão e gerenciamento de sua conta.';
  }

  public function getDescription(): string
  {
    return 'Conectar com o Copilot da FULL libera todos os superpoderes, ativações e melhoras disponíveis no seu WordPress.';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/connector-fill.svg';
  }

  public function getInputPlaceholder(): string
  {
    return 'Digite apenas o e-mail usado durante a compra das licenças na FULL.';
  }

  public function isDefault(): bool
  {
    return !User::instance()->isConnected();
  }

  public function isAvailable(): bool
  {
    return true;
  }

  public function isSoon(): bool
  {
    return false;
  }

  public function getFeaturesList(): array
  {
    return [
      'Novas habilidades desbloqueadas',
      'Consultar assentos disponíveis',
      'Acesso rápido para solicitar novas ativações',
      'Novos recursos incríveis',
    ];
  }

  public function agents(): array
  {
    return [
      new ConnectorAgent()
    ];
  }
}
