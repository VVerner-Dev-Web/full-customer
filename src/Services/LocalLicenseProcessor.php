<?php

namespace FC\Services;

class LocalLicenseProcessor
{
  private array $map;

  public function __construct()
  {
    $this->map = [
      'advanced-custom-fields-pro' => [$this, 'acfPRO'],
    ];
  }

  public function isAvailableForPlugin(string $plugin): bool
  {
    return isset($this->map[$plugin]);
  }

  public function process(string $plugin, string $license): bool
  {
    return call_user_func($this->map[$plugin], $license);
  }

  public function acfPRO(string $license): bool
  {
    if (!function_exists('acf_pro_activate_license')) {
      return false;
    }

    $done = acf_pro_activate_license($license, true);

    return is_array($done) && (int) $done['success'] === 1;
  }
}
