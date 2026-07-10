<?php

namespace FC\Models;

abstract class AbstractModel
{
  abstract public function getId(): string;

  abstract public function getName(): string;

  abstract public function getShortDescription(): string;

  abstract public function getDescription(): string;

  abstract public function getIcon(): string;

  abstract public function getInputPlaceholder(): string;

  abstract public function getFeaturesList(): array;

  /**
   * Retorna os agentes associados a este modelo.
   *
   * @return \FC\Agents\AbstractAgent[]
   */
  abstract public function agents(): array;

  public function isDefault(): bool
  {
    return false;
  }

  public function isAvailable(): bool
  {
    return false;
  }

  public function isSoon(): bool
  {
    return false;
  }
}
