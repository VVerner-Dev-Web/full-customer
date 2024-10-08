<?php

namespace Full\Customer;

use Full\Customer\Health;

class Proxy
{
  public const CRON_JOB_NAME  = 'full-customer/proxy';

  public static function enqueueCreateHook(): void
  {
    if (!wp_next_scheduled(self::CRON_JOB_NAME)) {
      wp_schedule_event(time(), 'daily', self::CRON_JOB_NAME);
    }
  }

  public static function cronJob(): void
  {
    if (fullCustomer()->isServiceEnabled('full-update')) {
      (new self())->sendData();
    }
  }

  protected function sendData(): void
  {
    $payload = [
      'plugins'   => $this->getPlugins(),
      'themes'    => $this->getThemes(),
      'wordpress' => $this->getWordPress()
    ];

    wp_remote_post($this->getUrl() . 'save', [
      'sslverify' => false,
      'blocking'  => false,
      'headers'   => [
        'Content-type'  => 'application/json'
      ],
      'body'      => wp_json_encode([
        'siteAddress'   => home_url(),
        'payload'       => $payload
      ])
    ]);
  }

  private function getPlugins(): array
  {
    if (!function_exists('get_plugins')) :
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    endif;

    $payload = [];
    $activePlugins = (array) get_option('active_plugins', []);
    $plugins       = get_plugins();

    foreach ($plugins as $key => $plugin) :
      $data = $this->filterPluginData($this->normalizeDataKeys($plugin));

      $data['plugin'] = $key;
      $data['status'] = in_array($key, $activePlugins) ? 'active' : 'inactive';

      $payload[] = $data;
    endforeach;

    return $payload;
  }

  private function normalizeDataKeys(array $data): array
  {
    $normalized = [];

    foreach ($data as $key => $value) :
      $normalized[lcfirst($key)] = $value;
    endforeach;

    return $normalized;
  }

  private function filterPluginData(array $data): array
  {
    $keys = [
      "name",
      "pluginURI",
      "version",
      "description",
      "author"
    ];

    $extraKeys = array_diff(
      array_keys($data),
      $keys
    );

    foreach ($extraKeys as $key) :
      unset($data[$key]);
    endforeach;

    return $data;
  }

  private function getThemes(): array
  {
    $payload = [];

    $themes = wp_get_themes();
    $currentTheme = wp_get_theme();

    foreach ($themes as $theme) :
      $parentTheme = $theme->parent();

      $payload[] = [
        'renderedName'  => $theme->Name,
        'themeURI'      => $theme->ThemeURI,
        'version'       => $theme->Version,
        'description'   => $theme->Description,
        'author'        => wp_strip_all_tags($theme->Author),
        'key'           => $theme->get_stylesheet(),
        'parent'        => $parentTheme ? $parentTheme->Name : null,
        'status'        => $currentTheme->get_stylesheet() === $theme->get_stylesheet() ? 'active' : 'inactive'
      ];
    endforeach;

    return $payload;
  }

  private function getWordPress(): array
  {
    $results = (new Health)->getResults();
    return isset($results['site_status']['direct']) ? $results['site_status']['direct'] : [];
  }

  private function getUrl(): string
  {
    $full = fullCustomer();
    $url  = 'PRD' === $full->getCurrentEnv() ? 'https://wpfull.com.br' : 'https://full-proxy.dev';

    return trailingslashit($url);
  }
}
