<?php

namespace Full\Customer\Speed;

defined('ABSPATH') || exit;

class Settings
{
  const PREFIX = 'speed-';

  public function set(string  $prop, $value): void
  {
    fullCustomer()->set(self::PREFIX . $prop, $value);
  }

  public function get(string  $prop)
  {
    return fullCustomer()->get(self::PREFIX . $prop);
  }
}
