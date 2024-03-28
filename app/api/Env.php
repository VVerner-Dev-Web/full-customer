<?php defined('ABSPATH') || exit;

class FullCustomer
{
  private const PREFIX = '_full_customer-';

  public function set(string  $prop, $value): void
  {
    update_option(self::PREFIX . $this->optionEnvSuffix() . $prop, $value, false);
  }

  public function get(string  $prop)
  {
    return get_option(self::PREFIX . $this->optionEnvSuffix() . $prop, null);
  }

  public function getBranding(string $prop, string $default = ''): ?string
  {
    if (!$this->isServiceEnabled('full-whitelabel')) :
      return $default;
    endif;

    $branding = $this->get('whitelabel_settings');
    $prop = str_replace('-', '_', $prop);

    return isset($branding[$prop]) && $branding[$prop] ? $branding[$prop] : $default;
  }

  public function hasDashboardUrl(): bool
  {
    return $this->get('dashboard_url') ? true : false;
  }

  public function getFullDashboardApiUrl(string $env = null): string
  {
    $env = $env ? strtoupper($env) : $this->getCurrentEnv();
    switch ($env):
      case 'DEV':
        $uri = 'https://full.dev/wp-json/full';
        break;
      case 'STG':
        $uri = 'https://somosafull.com.br/wp-json/full';
        break;
      default:
        $uri = 'https://painel.full.services/wp-json/full';
    endswitch;

    return $uri;
  }

  public function getCurrentEnv(): string
  {
    return defined('FULL_CUSTOMER') ? FULL_CUSTOMER : 'PRD';
  }

  public function setEnabledServices(array $services): void
  {
    $this->set('enabled_services', array_values($services));

    $url     = $this->getFullDashboardApiUrl() . '-customer/v1/widgets';
    $payload = [
      'site'  => site_url(),
      'widgets' => $this->getEnabledServices(),
    ];

    wp_remote_post($url, ['sslverify' => false, 'body' => $payload, 'blocking' => false]);
  }

  public function isServiceEnabled(string $service): bool
  {
    return array_search($service, $this->getEnabledServices(), true) !== false;
  }

  public function getEnabledServices(): array
  {
    if (is_null($this->get('enabled_services'))) :
      $default = [
        'full-bot',
        'full-cloud',
        'full-templates',
        'full-access',
        'full-clone',
        'full-update',
        'full-security',
        'full-ai',
      ];
      $this->set('enabled_services', $default);
    endif;

    return $this->get('enabled_services') ?? [];
  }

  private function optionEnvSuffix(): string
  {
    return 'PRD' === $this->getCurrentEnv() ? '' : 'dev-';
  }
}
