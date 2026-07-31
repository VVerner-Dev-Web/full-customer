<?php

declare(strict_types=1);

namespace FC\Agents;

use FC\Actions\RequestSupport;
use FC\FileSystem;

class TechAgent extends AbstractAgent
{
  public function getId(): string
  {
    return 'tecnicos-full';
  }

  public function getName(): string
  {
    return 'Técnicos FULL';
  }

  public function getIcon(): string
  {
    return FileSystem::instance()->getUrl('assets/images/icons/customer-service-fill.svg');
  }

  public function isReadOnly(): bool
  {
    return true;
  }

  public function actions(): array
  {
    return [
      new RequestSupport('suporte FC.Ai'),
    ];
  }
}
