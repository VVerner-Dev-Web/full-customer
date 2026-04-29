<?php

namespace FC\Actions;

use WP_REST_Request;
use WP_REST_Response;

class ExecutionStatus extends AbstractAction
{
  public function getIcon(): string
  {
    return '';
  }

  public function getName(): string
  {
    return 'Status da execução';
  }

  public function getShortDescription(): string
  {
    return 'Permite o usuário ver o status atual da execução de um processo';
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
    return 'actions/execution/(?P<processId>[a-zA-Z0-9-]+)';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    wp_cache_flush();

    $pid = $request->get_param('processId');

    return new WP_REST_Response([
      'state' => (string) get_transient('fc/current-state/' . $pid)
    ]);
  }

  public static function updateState(string $pid, string $state): void
  {
    set_transient('fc/current-state/' . $pid, $state, 60);
  }

  public static function deleteState(string $pid): void
  {
    delete_transient('fc/current-state/' . $pid);
  }
}
