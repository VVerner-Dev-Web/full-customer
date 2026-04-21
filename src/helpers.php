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

function fcDashboardAPI(): DashboardAPI
{
  return new DashboardAPI();
}

function fcRegisterRestRoute(string $method, string $route, callable $callback): void
{
  add_action(
    'rest_api_init',
    function () use ($method, $route, $callback) {
      register_rest_route(FULL_CUSTOMER_REST_NAMESPACE, $route, [
        'methods'  => $method,
        'callback' => $callback,
        'permission_callback' => fn() => current_user_can('manage_options'),
      ]);
    }
  );
}
