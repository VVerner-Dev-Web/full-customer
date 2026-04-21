<?php

namespace FC\Skills;

class Builder extends AbstractSkill
{
  const ID = 'builder';

  public function getName(): string
  {
    return 'Builder AI';
  }

  public function getShortDescription(): string
  {
    return 'Converta HTML em widget Elementor';
  }

  public function getDescription(): string
  {
    return 'Converta HTML em widget Elementor';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/embreve-builder.svg';
  }

  public function getInputPlaceholder(): string
  {
    return '';
  }


  public function isAvailable(): bool
  {
    return false;
  }
}
