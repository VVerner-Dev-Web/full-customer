<?php

namespace FC\Actions;

use FC\DashboardAPI;
use WP_REST_Request;
use WP_REST_Response;

class CleanUpCache extends AbstractAction
{
  public function getIcon(): string
  {
    return 'assets/images/icons/file-shred-line.svg';
  }

  public function getName(): string
  {
    return 'Limpar cache FULL.';
  }

  public function getShortDescription(): string
  {
    return 'Permite que técnicos e usuários do site possam limpar arquivos temporários da FULL.';
  }

  public function getPromptArgs(): array
  {
    return array_merge($this->_defaultPromptArgs(), [
      'id' => 'CleanUpCache',
      'simpleRest' => true
    ]);
  }

  public function getRestMethod(): string
  {
    return 'POST';
  }

  public function getRestRoute(): string
  {
    return 'actions/error-fix/clean-up-cache';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    do_action('fc/updates/invalidate');

    $version = get_option(DashboardAPI::VERSION_OPTION);
    update_option(DashboardAPI::VERSION_OPTION, $version + 1, false);

    return new WP_REST_Response([
      'success' => true,
      'message' => 'Cache de atualizações e da API limpos com sucesso!',
    ]);
  }
}
