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

    if (function_exists('apache_setenv')) {
      // phpcs:ignore
      @apache_setenv('no-gzip', '1');
    }
    @ini_set('zlib.output_compression', '0');
    @ini_set('implicit_flush', '1');

    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache, must-revalidate');
    header('Connection: keep-alive');
    header('X-Accel-Buffering: no');

    while (ob_get_level() > 0) {
      ob_end_flush();
    }
    ob_implicit_flush(1);

    $start_time = time();
    $last_state = null;
    $has_started = false;

    while (time() - $start_time < MINUTE_IN_SECONDS * 10) {
      if (connection_aborted()) {
        break;
      }

      wp_cache_flush();
      $raw_state = get_transient('fc/current-state/' . $pid);
      $state = $raw_state !== false ? (string) $raw_state : '';

      if ($state !== '') {
        $has_started = true;
      }

      if ($state !== $last_state) {
        $last_state = $state;
        echo "data: " . wp_json_encode(['state' => $state]) . "\n\n";
        if (ob_get_level() > 0) {
          ob_flush();
        }
        flush();
      }

      if ($has_started && $raw_state === false) {
        break;
      }

      sleep(1);
    }

    return new WP_REST_Response([]);
  }

  public static function updateState(string $pid, string $state): void
  {
    set_transient('fc/current-state/' . $pid, $state, MINUTE_IN_SECONDS);
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
