<?php defined('ABSPATH') || exit;

class FullCustomer
{
  private const PREFIX = '_full_customer-';

  public function set(string  $prop, $value): void
  {
    update_option(self::PREFIX . $prop, $value, false);
  }

  public function get(string  $prop)
  {
    return get_option(self::PREFIX . $prop, null);
  }

  public function getBranding(string $prop, string $default = ''): ?string
  {
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
}
