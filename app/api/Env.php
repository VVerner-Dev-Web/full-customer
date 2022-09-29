<?php defined('ABSPATH') || exit;

class FULL_CUSTOMER_Env
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

  public function isConnected(): bool
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
        $uri = 'https://painel.fullstackagency.club/wp-json/full';
    endswitch;

    return $uri;
  }

  public function getCurrentEnv(): string
  {
    return defined('FULL_CUSTOMER_ENV') ? FULL_CUSTOMER_ENV : 'PRD';
  }
}
