<?php

namespace FC\Actions;

use FC\User;
use WP_REST_Request;
use WP_REST_Response;

class AccountLicensesExtract extends AbstractAction
{
  public function getIcon(): string
  {
    return 'assets/images/icons/connector-fill.svg';
  }

  public function getName(): string
  {
    return 'Resumo de licenças';
  }

  public function getShortDescription(): string
  {
    return 'Consultar o consumo de licenças e ativações vinculados a sua conta FULL.';
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), [
      'id' => 'accountLicensesResume'
    ]);
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
    $data = fcDashboardAPI('GET', 'account/licenses-extract');

    if (!$data['success']) {
      return new WP_REST_Response([
        'success' => false,
        'error' => 'Não consegui recuperar o resumo de licenças, por favor verifique a conexão e tente novamente.'
      ]);
    }

    $message = '';

    foreach ($data['data'] as $i => $value) {
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
