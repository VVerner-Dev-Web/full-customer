<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class Settings
{
  const PREFIX = 'woocommerce-';

  public function set(string  $prop, $value): void
  {
    fullCustomer()->set(self::PREFIX . $prop, $value);
  }

  public function get(string  $prop)
  {
    return fullCustomer()->get(self::PREFIX . $prop);
  }

  public function hasWooCommerce(): bool
  {
    return function_exists('WC');
  }
}
