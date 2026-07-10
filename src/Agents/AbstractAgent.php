<?php

namespace FC\Agents;

abstract class AbstractAgent
{
  abstract public function getId(): string;

  abstract public function getName(): string;

  abstract public function getIcon(): string;

  /**
   * Retorna as ações associadas a este agente.
   *
   * @return \FC\Actions\AbstractAction[]
   */
  abstract public function actions(): array;

  public function isReadOnly(): bool
  {
    return false;
  }
}
