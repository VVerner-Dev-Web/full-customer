<?php

namespace FC\Skills;

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
    return 'Ative plugins PRO no seu site.';
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
}
