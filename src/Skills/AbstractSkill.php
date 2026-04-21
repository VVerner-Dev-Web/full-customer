<?php

namespace FC\Skills;

abstract class AbstractSkill
{
  abstract public function getName(): string;

  abstract public function getShortDescription(): string;

  abstract public function getDescription(): string;

  abstract public function getIcon(): string;

  abstract public function getInputPlaceholder(): string;

  abstract public function isAvailable(): bool;
}
