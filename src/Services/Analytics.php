<?php

namespace FC\Services;

class Analytics
{
  public function __construct()
  {
    register_activation_hook(FULL_CUSTOMER_FILE, [$this, 'activationAnalyticsHook']);
    register_deactivation_hook(FULL_CUSTOMER_FILE, [$this, 'deactivationAnalyticsHook']);

    add_filter('wp_php_error_args', [$this, 'storePluginError'], PHP_INT_MAX, 2);
    add_action('shutdown', [$this, 'notifyPluginError']);
  }

  public function storePluginError(array $args, array $error): array
  {
    if (strpos($error['file'], dirname(FULL_CUSTOMER_FILE)) !== false) {
      update_option('fc/last-error', array_merge($error, ['date' => current_time('mysql')]), false);
    }

    return $args;
  }

  public function notifyPluginError(): void
  {
    $error = get_option('fc/last-error');

    if (!$error) {
      return;
    }

    fcDashboardAPI('POST', 'analytics/fc-error', [
      'error' => $error
    ]);

    delete_option('fc/last-error');
  }

  public function activationAnalyticsHook(): void
  {
    fcDashboardAPI('POST', 'analytics/fc-status/active');
  }

  public function deactivationAnalyticsHook(): void
  {
    fcDashboardAPI('POST', 'analytics/fc-status/inactive');
  }
}
