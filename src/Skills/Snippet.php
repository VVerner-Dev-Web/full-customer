<?php

namespace FC\Skills;

class Snippet extends AbstractSkill
{
  const ID = 'snippet';

  public function getName(): string
  {
    return 'Snippets AI';
  }

  public function getShortDescription(): string
  {
    return 'Gere e aplique trechos de código';
  }

  public function getDescription(): string
  {
    return 'Gere e aplique trechos de código';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/embreve-snippets.svg';
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
