<?php

namespace FC\Actions;

use WP_REST_Request;
use WP_REST_Response;

class PluginLicenseExtract extends AbstractAction
{
  private ?array $repoPlugin = null;

  public function __construct(?array $repoPlugin = null)
  {
    $this->repoPlugin = $repoPlugin;
  }

  public function getIcon(): string
  {
    return 'assets/images/icons/archive-drawer-line.svg';
  }

  public function getName(): string
  {
    return 'Consultar licenças compradas';
  }

  public function getShortDescription(): string
  {
    return 'Consultar o consumo de licenças e ativações vinculados a sua conta FULL.';
  }

  public function getPromptArgs(): array
  {
    $args = $this->_defaultPromptArgs();

    if ($this->repoPlugin) {
      $args = array_merge($args, [
        'id' => 'accountLicensesResume.' . $this->repoPlugin['id'],
        'extraProps' => [
          'pluginId' => $this->repoPlugin['id']
        ]
      ]);
    }

    return $args;
  }

  public function getRestMethod(): string
  {
    return 'GET';
  }

  public function getRestRoute(): string
  {
    return 'actions/account/licenses-extract';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $pluginId = $request->get_param('pluginId');
    $params = [];
    if ($pluginId) {
      $params['pluginId'] = $pluginId;
    }

    $data = fcDashboardAPI('GET', 'account/licenses-extract', $params);

    if (!$data['success']) {
      return new WP_REST_Response([
        'success' => false,
        'error' => 'Não consegui recuperar o resumo de licenças, por favor verifique a conexão e tente novamente.'
      ]);
    }

    $message = '';

    $plugins = isset($data['data']['name']) ? [$data['data']] : $data['data'];

    foreach ($plugins as $i => $value) {
      if ($i > 0) {
        $message .= '<hr>';
      }
      $message .= '<h6>' . $value['name'] . '</h6><p>';
      $message .= 'Você comprou ' . $value['purchased'] . _n(' licença ', ' licenças ', $value['purchased']);
      $message .= 'e tem <strong>' . $value['available'] . _n(' licença disponível', ' licenças disponíveis', $value['available']) . '</strong> para uso';

      if ($value['domainsInUse']) {
        $message .= '. Atualmente você tem licenças conectadas em: ' . implode(', ', $value['domainsInUse']);
      }

      $message .= '.</p>';
    }

    return new WP_REST_Response([
      'success' => true,
      'message' => $message
    ]);
  }
}
