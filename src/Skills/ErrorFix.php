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
    return 'Detecta e corrige automaticamente os erros mais comuns do WordPress — tela branca, conflitos de extensões, erros 500, falhas de banco de dados — sem precisar abrir o terminal ou contatar o suporte.';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/embreve-errorfix.svg';
  }

  public function getInputPlaceholder(): string
  {
    return '';
  }

  public function getFeaturesList(): array
  {
    return [
      'Diagnóstico automático em tempo real',
      'Correção com um clique',
      'Biblioteca de 200+ erros conhecidos',
      'Reversão segura se a correção falhar',
    ];
  }
}
