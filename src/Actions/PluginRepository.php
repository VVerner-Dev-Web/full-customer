<?php

namespace FC\Actions;

use WP_REST_Request;
use WP_REST_Response;

class PluginRepository extends AbstractAction
{
  public function getIcon(): string
  {
    return 'assets/images/icons/zip.svg';
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
    $data = fcDashboardAPI('GET', 'plugin-repository/all');
    $plugins = $data['success'] ? $data['data'] : [];

    $message = '';

    foreach ($plugins as $i => $plugin) {
      if ($i > 0) {
        $message .= '<hr>';
      }

      $message .= '<h6>' . $plugin['name'] . '</h6>';
      $message .= '<p>Versão disponível: ' . $plugin['version'] . '</p>';
      $message .= $plugin['activation']['id'] !== 0 ?
        '<p>Este plugin já foi ativado neste site</p>' :
        '<p>Plugin disponível para ativação</p>';
    }

    return new WP_REST_Response([
      'message' => $message,
      'plugins' => $plugins,
      'success' => !empty($plugins)
    ]);
  }
}
