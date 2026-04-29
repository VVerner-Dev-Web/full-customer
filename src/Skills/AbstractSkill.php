<?php

namespace FC\Skills;

use FC\FileSystem;

abstract class AbstractSkill
{
  abstract public function getName(): string;

  abstract public function getShortDescription(): string;

  abstract public function getDescription(): string;

  abstract public function getIcon(): string;

  abstract public function getInputPlaceholder(): string;

  abstract public function getFeaturesList(): array;

  public function isAvailable(): bool
  {
    return false;
  }

  public function isSoon(): bool
  {
    return true;
  }

  public function actions(): array
  {
    return [];
  }
}
