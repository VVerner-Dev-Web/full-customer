<?php

namespace FC\Actions;

use FC\User;
use WP_REST_Request;
use WP_REST_Response;

class ViewConnectedAccount extends AbstractAction
{
  public function getIcon(): string
  {
    return 'assets/images/icons/link-m.svg';
  }

  public function getName(): string
  {
    return 'Ver Conta Conectada';
  }

  public function getShortDescription(): string
  {
    return 'Consultar a conta FULL conectada atualmente com seu usuário';
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), [
      'id' => 'viewConnectedAccount'
    ]);
  }

  public function getRestMethod(): string
  {
    return 'GET';
  }

  public function getRestRoute(): string
  {
    return 'actions/account/view';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    return new WP_REST_Response([
      'success' => true,
      'message' => 'Atualmente você está conectado com a conta <strong>'  . User::instance()->getConnectionEmail() . '</strong>.',
      'actions' => [
        [
          'label' => 'Voltar à home',
          'action' => 'restart-chat',
        ],
        [
          'label' => 'Ativar licenças',
          'action' => 'skill.activateProPlugin',
        ]
      ]
    ]);
  }
}
