<?php

namespace FC;

use FC\Models\AbstractModel;
use FC\Models\ConnectModel;
use FC\Models\ErrorFixModel;
use FC\Models\PluginsModel;
use FC\Models\SupportModel;

final class ModelRepository
{
  private array $models = [];
  private static $instance = null;

  private function __construct()
  {
    $this->models[ConnectModel::ID] = new ConnectModel();
    $this->models[PluginsModel::ID] = new PluginsModel();
    $this->models[ErrorFixModel::ID] = new ErrorFixModel();
    $this->models[SupportModel::ID] = new SupportModel();
  }

  public function toArray(): array
  {
    $list = [];

    foreach ($this->all() as $key => $model) {
      $list[$key] = [
        'id' => $model->getId(),
        'name' => $model->getName(),
        'shortDescription' => $model->getShortDescription(),
        'description' => $model->getDescription(),
        'icon' => FileSystem::instance()->getUrl($model->getIcon()),
        'inputPlaceholder' => $model->getInputPlaceholder(),
        'isAvailable' => $model->isAvailable()
      ];
    }

    return $list;
  }

  public static function instance(): self
  {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function all(string $status = 'any'): array
  {
    $list = $this->models;

    if ('available' === $status) {
      $list = array_filter($this->models, fn(AbstractModel $model): bool => $model->isAvailable());
    } elseif ('unavailable' === $status) {
      $list = array_filter($this->models, fn(AbstractModel $model): bool => !$model->isAvailable());
    }

    return $list;
  }

  public function get(string $key): ?AbstractModel
  {
    return isset($this->models[$key]) ? $this->models[$key] : null;
  }
}
