<?php

namespace FC\Fragments;

use FC\FileSystem;

abstract class AbstractFragment
{
  protected array $args;

  public function __construct(array $args = [])
  {
    $this->args = $args;
  }

  protected function get_arg(string $key, $default = null)
  {
    return $this->args[$key] ?? $default;
  }

  public function render(): string
  {
    ob_start();

    include FileSystem::instance()->resolvePath($this->filename());

    return ob_get_clean();
  }

  abstract public function filename(): string;
}
