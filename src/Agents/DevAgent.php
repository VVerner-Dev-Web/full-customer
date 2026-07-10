<?php

namespace FC\Agents;

use FC\Actions\CleanUpCache;
use FC\FileSystem;

class DevAgent extends AbstractAgent
{
  public function getId(): string
  {
    return 'dev';
  }

  public function getName(): string
  {
    return 'Dev';
  }

  public function getIcon(): string
  {
    return FileSystem::instance()->getUrl('assets/images/icons/errorfix.svg');
  }

  public function isReadOnly(): bool
  {
    return true;
  }

  public function actions(): array
  {
    return [
      new CleanUpCache(),
    ];
  }
}
