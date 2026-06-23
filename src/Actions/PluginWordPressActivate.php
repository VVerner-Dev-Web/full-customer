<?php

namespace FC\Actions;

use WP_REST_Request;
use WP_REST_Response;

class PluginWordPressActivate extends AbstractAction
{
  public function getIcon(): string
  {
    return '';
  }

  public function getName(): string
  {
    return 'Ativar plugin';
  }

  public function getShortDescription(): string
  {
    return 'Permite o usuário ativar os plugins que ele tem pela FULL.';
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
    return 'actions/plugins/wordpress-activate/(?P<processId>[a-zA-Z0-9-]+)';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $plugin = $request->get_param('plugin');

    if (is_plugin_active($plugin)) {
      return new WP_REST_Response([
        'success' => true,
        'message' => 'O plugin já está ativo no site, podemos continuar rapidamente.'
      ]);
    }

    if (!function_exists('get_editable_roles')) {
      require_once ABSPATH . 'wp-admin/includes/user.php';
    }

    $status = activate_plugin($plugin);

    if (is_wp_error($status)) {
      return new WP_REST_Response([
        'success' => false,
        'error' => $status->get_error_message()
      ]);
    }

    do_action('fc/updates/invalidate');

    return new WP_REST_Response([
      'success' => true,
      'message' => 'Plugin ativado no site.'
    ]);
  }
}
