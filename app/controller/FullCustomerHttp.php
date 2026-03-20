<?php

defined('ABSPATH') || exit;

class FullCustomerHttp
{
  public function __construct()
  {
    add_filter('wp_is_application_passwords_available', '__return_true', PHP_INT_MAX);
    add_filter('wp_is_application_passwords_available_for_user', '__return_true', PHP_INT_MAX);

    add_filter('http_request_args', [$this, 'filterRequestArgs'], PHP_INT_MAX, 2);
    add_filter('rest_pre_serve_request', [$this, 'restPreServeRequest'], 0, 2);
  }

  public function filterRequestArgs(array $args, $url): array
  {
    if (is_string($url) && $url && strpos($url, getFullDashboardApiUrl()) !== false) {
      $args['reject_unsafe_urls'] = 'PRD' === getFullEnv();
      $args['sslverify'] = 'PRD' === getFullEnv();
      $args['headers']['X-Full-Site'] = trailingslashit(home_url());
    }

    return $args;
  }

  public function restPreServeRequest(bool $served, WP_REST_Response $response): bool
  {
    if ($served) {
      return $served;
    }

    $buffer   = null;

    foreach (array_keys($response->get_headers()) as $header) {
      if ('x-full' === strtolower($header)) {
        $buffer   = $response->get_data();
        break;
      }
    }

    if (!is_string($buffer)) {
      return $served;
    }

    echo $buffer;
    return true;
  }
}

new FullCustomerHttp();
