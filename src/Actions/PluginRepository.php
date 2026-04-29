<?php

namespace FC\Actions;

use FC\PluginRepository as FCPluginRepository;
use WP_REST_Request;
use WP_REST_Response;

class PluginRepository extends AbstractAction
{
  public function getIcon(): string
  {
    return '';
  }

  public function getName(): string
  {
    return 'Todos os plugins adquiridos com a FULL.';
  }

  public function getShortDescription(): string
  {
    return 'Permite o usuário ver todos os plugins que ele tem acesso pela FULL.';
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), []);
  }

  public function getRestMethod(): string
  {
    return 'POST';
  }

  public function getRestRoute(): string
  {
    return 'actions/plugins/repository';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $plugins = (new FCPluginRepository())->getPlugins(true);

    return new WP_REST_Response([
      'plugins' => $plugins,
      'success' => !empty($plugins)
    ]);
  }
}
