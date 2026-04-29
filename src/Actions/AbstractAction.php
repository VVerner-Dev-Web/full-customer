<?php

namespace FC\Actions;

use FC\FileSystem;
use FC\User;
use WP_REST_Request;
use WP_REST_Response;

abstract class AbstractAction
{
  abstract public function getIcon(): string;
  abstract public function getName(): string;
  abstract public function getShortDescription(): string;
  abstract public function getPromptArgs(): array;

  abstract public function getRestMethod(): string;
  abstract public function getRestRoute(): string;
  abstract public function restHandler(WP_REST_Request $request): WP_REST_Response;

  public function getCta(): string
  {
    return 'Executar';
  }

  public function isAvailable(): bool
  {
    return User::instance()->isConnected();
  }

  public function isShell(): bool
  {
    return !empty($this->inShellActions());
  }

  public function inShellActions(): array
  {
    return [];
  }

  protected function _defaultPromptArgs(): array
  {
    return [
      'id' => sanitize_title(get_class($this)),
      'imageUrl' => FileSystem::instance()->getUrl($this->getIcon()),
      'name' => $this->getName(),
      'desc' => $this->getShortDescription(),
      'isAvailable' => $this->isAvailable(),
      'restRoute' => $this->getRestRoute(),
      'restMethod' => $this->getRestMethod(),
      'simpleRest' => true
    ];
  }
}
