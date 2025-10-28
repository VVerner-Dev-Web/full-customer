<?php

defined('ABSPATH') || exit;

class FullCustomerUpdate
{
  const TRANSIENT_KEY = 'full_customer_update_info';
  const TRANSIENT_TTL = DAY_IN_SECONDS;

  public function __construct()
  {
    add_filter('plugins_api', [$this, 'pluginInfo'], PHP_INT_MAX, 3);
    add_filter('site_transient_update_plugins', [$this, 'pluginUpdate']);
    add_filter('http_request_args', [$this, 'filterRequestArgs'], PHP_INT_MAX, 2);
  }

  public function filterRequestArgs(array $args, string $url)
  {
    $fullUrl = fullCustomer()->getFullDashboardApiUrl();

    if (strpos($url, $fullUrl) !== false) {
      $args['reject_unsafe_urls'] = false;
      $args['sslverify'] = false;
    }

    return $args;
  }

  public function pluginInfo($res, $action, $args)
  {
    if ($action !== 'plugin_information') {
      return $res;
    }

    if (plugin_basename(dirname(FULL_CUSTOMER_FILE)) !== $args->slug) {
      return $res;
    }

    $data = $this->fetchPluginUpdate();
    return $data ?: $res;
  }

  public function pluginUpdate($transient)
  {
    if (!$transient || empty($transient->checked)) {
      return $transient;
    }

    $data = $this->fetchPluginUpdate();
    if (!$data) {
      return $transient;
    }

    if (
      version_compare(FULL_CUSTOMER_VERSION, $data->version, '<') &&
      version_compare($data->requires, get_bloginfo('version'), '<') &&
      version_compare($data->requires_php, PHP_VERSION, '<')
    ) {
      $res = (object) [
        'slug'        => $data->slug,
        'plugin'      => plugin_basename(FULL_CUSTOMER_FILE),
        'new_version' => $data->version,
        'tested'      => $data->tested,
        'package'     => $data->download_url,
      ];

      $transient->response[$res->plugin] = $res;
    }

    return $transient;
  }

  private function fetchPluginUpdate(): ?stdClass
  {
    $cached = get_transient(self::TRANSIENT_KEY);
    if ($cached !== false) {
      return $cached;
    }

    $url = untrailingslashit(fullCustomer()->getFullDashboardApiUrl()) . '/v1/plugin/info/full-customer';

    $response = wp_remote_get($url, [
      'sslverify' => false,
      'headers'   => ['Accept' => 'application/json'],
      'timeout'   => 15,
    ]);

    if (
      is_wp_error($response) ||
      wp_remote_retrieve_response_code($response) !== 200
    ) {
      return null;
    }

    $body = wp_remote_retrieve_body($response);
    if (empty($body)) {
      return null;
    }

    $data = json_decode($body);
    if (empty($data)) {
      return null;
    }

    if (isset($data->sections) && is_object($data->sections)) {
      $data->sections = (array) $data->sections;
    }

    set_transient(self::TRANSIENT_KEY, $data, self::TRANSIENT_TTL);

    return $data;
  }
}

new FullCustomerUpdate();
