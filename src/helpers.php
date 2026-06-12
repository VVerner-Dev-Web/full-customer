<?php

use FC\DashboardAPI;

function fcElementDataFragments(string $fragment, string $target, array $args = []): string
{
  return sprintf(
    ' data-fragment="%s" data-target="%s" data-args=\'%s\' ',
    $fragment,
    $target,
    wp_json_encode($args)
  );
}

function fcDashboardAPI(string $method, string $endpoint, array $payload = []): array
{
  return (new DashboardAPI())->fetch($method, $endpoint, $payload);
}

function fcRegisterRestRoute(string $method, string $route, callable $callback, ?callable $permissionCallback = null): void
{
  add_action(
    'rest_api_init',
    function () use ($method, $route, $callback, $permissionCallback) {
      register_rest_route(FULL_CUSTOMER_REST_NAMESPACE, $route, [
        'methods'  => $method,
        'callback' => $callback,
        'permission_callback' => $permissionCallback ?? fn() => current_user_can('manage_options'),
      ]);
    }
  );
}

function fcGetAnonymousUserConnection(): ?array
{
  static $data = false;

  if ($data === false) {
    global $wpdb;
    $data = $wpdb->get_row("SELECT user_id, meta_value as connection_email FROM {$wpdb->usermeta} WHERE meta_key = 'fc/connection-email' AND meta_value != '' LIMIT 1", ARRAY_A);
    $data =  is_array($data) && $data ? $data : null;
  }

  return $data;
}
