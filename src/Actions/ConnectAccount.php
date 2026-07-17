<?php

namespace FC\Actions;

use FC\User;
use WP_REST_Request;
use WP_REST_Response;

class ConnectAccount extends AbstractAction
{
  public function getIcon(): string
  {
    return 'assets/images/icons/link.svg';
  }

  public function getName(): string
  {
    return 'Conectar conta FULL.';
  }

  public function getShortDescription(): string
  {
    return 'Permite o usuário conectar a sua conta FULL com o usuário atual do WP.';
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), [
      'id' => 'connectAccount',
      'simpleRest' => false
    ]);
  }

  public function isAvailable(): bool
  {
    return !User::instance()->isConnected();
  }

  public function getRestMethod(): string
  {
    return 'POST';
  }

  public function getRestRoute(): string
  {
    return 'actions/account/connect';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $email = sanitize_email($request->get_param('email'));

    if (!is_email($email)) {
      return new WP_REST_Response([
        'error' => 'E-mail inválido ou ausente, por favor informe apenas o e-mail da sua conta FULL.'
      ]);
    }

    $success = $this->handleConnection($email);

    return new WP_REST_Response([
      'success' => $success['success'],
      'message' => $success['success'] ?
        'Conectado com sucesso! Vamos recarregar a página para que você possa aproveitar ao máximo sua conta FULL.' :
        $success['data']['error']
    ]);
  }

  public function handleConnection(string $email): array
  {
    define('FULL_CUSTOMER_CONNECTION_EMAIL', $email);

    $connected = fcDashboardAPI('POST', 'account/connect');
    $success = $connected['success'] && isset($connected['data']['success']) && $connected['data']['success'];

    if ($success) {
      User::instance()->setConnectionEmail($email);
    }

    return [
      'success' => $success,
      'data' => $connected['data'] ?? ['error' => $connected['message'] ?? 'Erro de conexão com o painel da FULL.']
    ];
  }

  public function updateSiteUrl(string $previousUrl): array
  {
    $update = fcDashboardAPI('POST', 'account/update-site-url', [
      'previousUrl' => $previousUrl
    ]);

    return [
      'success' => $update['success'],
      'data' => $update['data']
    ];
  }
}
