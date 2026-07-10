<?php

namespace FC\Agents;

use FC\Actions\PluginLicenseExtract;
use FC\Actions\PluginActivationFactory;
use FC\Actions\PluginActivationManager;
use FC\Actions\PluginReactivate;
use FC\FileSystem;

class PluginAgent extends AbstractAgent
{
  protected array $pluginData;

  public function __construct(array $pluginData)
  {
    $this->pluginData = $pluginData;
  }

  public function getId(): string
  {
    return $this->pluginData['slug'];
  }

  public function getName(): string
  {
    return $this->pluginData['name'];
  }

  public function getIcon(): string
  {
    return isset($this->pluginData['image_url']) && $this->pluginData['image_url']
      ? $this->pluginData['image_url']
      : FileSystem::instance()->getUrl('assets/images/icons/energy.svg');
  }

  public function actions(): array
  {
    $actions = [];

    if (!isset($this->pluginData['activation']) || intval($this->pluginData['activation']['id']) === 0) {
      $actions[] = new PluginActivationFactory($this->pluginData);
    } else {
      $actions[] = new PluginActivationManager($this->pluginData);
      $actions[] = new PluginReactivate($this->pluginData);
    }

    $actions[] = new PluginLicenseExtract($this->pluginData);

    return $actions;
  }
}
