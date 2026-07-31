<?php

declare(strict_types=1);

namespace FC\Models;

use FC\Agents\TechAgent;
use FC\User;

class SupportModel extends AbstractModel
{
  const ID = 'support';

  public function getId(): string
  {
    return self::ID;
  }

  public function getName(): string
  {
    return 'Suporte';
  }

  public function getShortDescription(): string
  {
    return 'Solicite suporte para a equipe da FULL.';
  }

  public function getDescription(): string
  {
    return 'Entre em contato direto com a equipe de técnicos da FULL. para tirar dúvidas, relatar problemas ou obter atendimento especializado.';
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/customer-service.svg';
  }

  public function getInputPlaceholder(): string
  {
    return 'Digite a sua solicitação de suporte...';
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
      'Atendimento técnico especializado',
      'Suporte direto da equipe FULL',
      'Resolução rápida de dúvidas e problemas',
    ];
  }

  public function agents(): array
  {
    return [
      new TechAgent()
    ];
  }
}
