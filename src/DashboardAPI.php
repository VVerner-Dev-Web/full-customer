<?php

namespace FC;

class DashboardAPI
{
  public function __construct() {}

  public function fetch(string $method, string $endpoint, array $payload = []): array
  {
    $method = strtoupper($method);
    $url    = FULL_CUSTOMER_API_URL . '/' . ltrim($endpoint, '/');

    if ($method === 'GET' && !empty($payload)) {
      $url = add_query_arg($payload, $url);
    }

    $args = [
      'method'      => $method,
      'timeout'     => 60,
      'redirection' => 5,
      'blocking'    => true,
      'body'        => $method !== 'GET' ? wp_json_encode($payload) : null,
      'headers'     => [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
      ],
    ];

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
      return [
        'success' => false,
        'message' => $response->get_error_message()
      ];
    }

    $code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if ($code < 200 || $code >= 300) {
      return [
        'success' => false,
        'status'  => $code,
        'message' => $body['message'] ?? $body['errors'] ?? 'Erro desconhecido na API.',
        'data'    => $body
      ];
    }

    return [
      'success' => true,
      'status'  => $code,
      'data'    => $body
    ];
  }
}
