<?php

namespace FC\Skills;

use FC\User;

class ActivateProPlugin extends AbstractSkill
{
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

  public function isAvailable(): bool
  {
    return User::instance()->isConnected();
  }
}
