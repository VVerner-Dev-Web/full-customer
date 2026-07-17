<?php

namespace FC\Actions;

use FC\User;
use WP_REST_Request;
use WP_REST_Response;

class DisconnectAccount extends AbstractAction
{
  public function getIcon(): string
  {
    return 'assets/images/icons/unlink.svg';
  }

  public function getName(): string
  {
    return 'Desconectar conta FULL.';
  }

  public function getShortDescription(): string
  {
    return 'Permite o usuário desconectar a sua conta FULL com o usuário atual do WP.';
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), [
      'id' => 'disconnectAccount',
      'simpleRest' => true
    ]);
  }

  public function getRestMethod(): string
  {
    return 'POST';
  }

  public function getRestRoute(): string
  {
    return 'actions/account/disconnect';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    User::instance()->setConnectionEmail('');

    return new WP_REST_Response([
      'success' => true,
      'message' => 'Conta desconectada com sucesso. Suas ativações e licenças seguem funcionando normalmente.',
    ]);
  }
}
