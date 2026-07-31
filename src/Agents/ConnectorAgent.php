<?php

namespace FC\Agents;

use FC\Actions\PluginLicenseExtract;
use FC\Actions\ConnectAccount;
use FC\Actions\DisconnectAccount;
use FC\Actions\PluginRepository;
use FC\Actions\RequestSupport;
use FC\Actions\ViewConnectedAccount;
use FC\FileSystem;

class ConnectorAgent extends AbstractAgent
{
  public function getId(): string
  {
    return 'conector';
  }

  public function getName(): string
  {
    return 'Conector';
  }

  public function getIcon(): string
  {
    return FileSystem::instance()->getUrl('assets/images/icons/agent-connector.svg');
  }

  public function isReadOnly(): bool
  {
    return true;
  }

  public function actions(): array
  {
    return [
      new ConnectAccount(),
      new ViewConnectedAccount(),
      new DisconnectAccount(),
      new PluginLicenseExtract(),
      new PluginRepository(),
      new RequestSupport('suporte sobre conta FULL'),
    ];
  }
}
