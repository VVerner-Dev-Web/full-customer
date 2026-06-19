<?php

namespace FC\Skills;

use FC\Actions\CleanUpCache;
use FC\Actions\PluginRepository;

class ErrorFix extends AbstractSkill
{
  const ID = 'errorFix';

  public function getName(): string
  {
    return 'Manutenção';
  }

  public function getShortDescription(): string
  {
    return 'Corrija os erros mais comuns de seu WordPress';
  }

  public function getDescription(): string
  {
    return 'Detecta e corrige os erros mais comuns do WordPress e da sua conta FULL. — tela branca, conflitos de extensões, erros 500, falhas de banco de dados — sem precisar abrir o terminal ou contatar o suporte.';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/errorfix.svg';
  }

  public function isAvailable(): bool
  {
    return true;
  }

  public function isSoon(): bool
  {
    return false;
  }

  public function getInputPlaceholder(): string
  {
    return 'Clique para selecionar uma ação';
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

  public function actions(): array
  {
    return [
      new CleanUpCache,
      new PluginRepository
    ];
  }
}
