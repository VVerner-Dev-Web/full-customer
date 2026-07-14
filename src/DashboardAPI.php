<?php

namespace FC;

class DashboardAPI
{
  public const VERSION_OPTION = 'fc_dapi_repo_version';

  private const CACHE_PREFIX = 'fc_dapi_';
  private const CACHEABLE_GROUP = 'plugin-repository/';

  public function __construct()
  {
  }

  public function fetch(string $method, string $endpoint, array $payload = []): array
  {
    $method = strtoupper($method);
    $endpoint = ltrim($endpoint, '/');
    $url = FULL_CUSTOMER_API_URL . '/' . $endpoint;

    if (FULL_CUSTOMER_DEV) {
      $mock = apply_filters('fc/dashboard-api/response', null, $method, $endpoint, $payload);

      if (!is_null($mock)) {
        return $mock;
      }
    }

    if ($method === 'GET' && !empty($payload)) {
      $url = add_query_arg($payload, $url);
    }

    $cacheKey = $this->isCacheable($method, $endpoint) ? $this->cacheKey($url) : null;

    if ($cacheKey !== null) {
      $cached = get_transient($cacheKey);

      if ($cached !== false) {
        return $cached;
      }
    }

    $args = [
      'method' => $method,
      'timeout' => MINUTE_IN_SECONDS * 5,
      'redirection' => 5,
      'blocking' => true,
      'body' => $method !== 'GET' ? wp_json_encode($payload) : null,
      'headers' => [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
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
        'status' => $code,
        'message' => $body['error'] ?? $body['errors'] ?? 'Erro desconhecido na API.',
        'data' => $body
      ];
    }

    $result = [
      'success' => true,
      'status' => $code,
      'data' => $body
    ];

    if ($cacheKey !== null) {
      set_transient($cacheKey, $result, 6 * HOUR_IN_SECONDS);
    }

    if ($method !== 'GET' && strpos($endpoint, self::CACHEABLE_GROUP) === 0) {
      $this->flushRepositoryCache();
    }

    return $result;
  }

  private function isCacheable(string $method, string $endpoint): bool
  {
    return
      $method === 'GET' &&
      strpos($endpoint, self::CACHEABLE_GROUP) === 0 &&
      strpos($endpoint, 'updates') === false;
  }

  private function cacheKey(string $url): string
  {
    $version = (int) get_option(self::VERSION_OPTION, 1);
    return self::CACHE_PREFIX . $version . '_' . md5($url) . '_' . get_current_user_id();
  }

  private function flushRepositoryCache(): void
  {
    $version = (int) get_option(self::VERSION_OPTION, 1);
    update_option(self::VERSION_OPTION, $version + 1, false);
  }
}
