<?php

namespace FC\Skills;

use FC\Actions\AccountLicensesExtract;
use FC\Actions\ConnectAccount;
use FC\Actions\DisconnectAccount;
use FC\Actions\ViewConnectedAccount;
use FC\User;

class Connect extends AbstractSkill
{
  const ID = 'connect';

  public function getName(): string
  {
    return 'Conectar';
  }

  public function getShortDescription(): string
  {
    return 'Conectar com o Copilot da FULL.';
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

  public function actions(): array
  {
    return [
      new ConnectAccount,
      new ViewConnectedAccount,
      new DisconnectAccount,
      new AccountLicensesExtract
    ];
  }

  public function getFeaturesList(): array
  {
    return [
      'Novas skills desbloqueadas',
      'Consultar assentos disponíveis',
      'Acesso rápido para solicitar novas ativações',
      'Novos recursos incríveis',
    ];
  }
}
