<?php

namespace FC\Models;

use FC\Agents\DevAgent;
use FC\User;

class ErrorFixModel extends AbstractModel
{
  const ID = 'errorFix';

  public function getId(): string
  {
    return self::ID;
  }

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

  public function getInputPlaceholder(): string
  {
    return 'Clique para selecionar uma ação';
  }

  public function isAvailable(): bool
  {
    return User::instance()->isConnected();
  }

  public function isSoon(): bool
  {
    return false;
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

  public function agents(): array
  {
    return [
      new DevAgent()
    ];
  }
}
