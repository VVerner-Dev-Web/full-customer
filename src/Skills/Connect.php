<?php

namespace FC\Skills;

class Connect extends AbstractSkill
{
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
    return 'Conectar com o Copilot da FULL para liberar superpoderes no seu WordPress.';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/connector-fill.svg';
  }

  public function isAvailable(): bool
  {
    return true;
  }
}
