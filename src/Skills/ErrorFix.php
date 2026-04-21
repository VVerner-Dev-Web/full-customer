<?php

namespace FC\Skills;

class ErrorFix extends AbstractSkill
{
  const ID = 'errorFix';

  public function getName(): string
  {
    return 'Error Auto Fix';
  }

  public function getShortDescription(): string
  {
    return 'Detecte e corrija erros do WordPress';
  }

  public function getDescription(): string
  {
    return 'Detecte e corrija erros do WordPress';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/embreve-errorfix.svg';
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
