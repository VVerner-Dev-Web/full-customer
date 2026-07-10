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
    return 'GET';
  }

  public function getRestRoute(): string
  {
    return 'actions/execution/(?P<processId>[a-zA-Z0-9-]+)';
  }

  public function restHandler(WP_REST_Request $request): WP_REST_Response
  {
    $pid = $request->get_param('processId');
    $key = 'fc/current-state/' . $pid;

    wp_cache_delete($key, 'transient');
    $states = get_transient($key);
    $states = is_array($states) ? $states : [];

    delete_transient($key);

    return new WP_REST_Response([
      'success' => true,
      'states'   => $states
    ]);
  }

  public static function updateState(string $pid, string $state): void
  {
    $key = 'fc/current-state/' . $pid;
    wp_cache_delete($key, 'transient');
    $current = get_transient($key);
    $states = is_array($current) ? $current : [];

    if (empty($states) || end($states) !== $state) {
      $states[] = $state;
      set_transient($key, $states, MINUTE_IN_SECONDS);
    }
  }

  public static function deleteState(string $pid): void
  {
    delete_transient('fc/current-state/' . $pid);
  }

  public static function setActivationId(string $pid, int $activationId): void
  {
    set_transient('fc/activation-id/' . $pid, $activationId, HOUR_IN_SECONDS);
  }
}
