<?php

namespace Full\Customer\Analytics;

defined('ABSPATH') || exit;

class Settings
{
  const PREFIX = 'analytics-';

  public function set(string  $prop, $value): void
  {
    fullCustomer()->set(self::PREFIX . $prop, $value);
  }

  public function get(string  $prop)
  {
    return fullCustomer()->get(self::PREFIX . $prop);
  }

  public function journeys(): array
  {
    return is_array($this->get('journeys')) ? $this->get('journeys') : [];
  }
}
