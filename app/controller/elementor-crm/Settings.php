<?php

namespace Full\Customer\ElementorCrm;

defined('ABSPATH') || exit;

class Settings
{
  const PREFIX = 'elementor-crm-';

  public function set(string  $prop, $value): void
  {
    fullCustomer()->set(self::PREFIX . $prop, $value);
  }

  public function get(string  $prop)
  {
    return fullCustomer()->get(self::PREFIX . $prop);
  }

  public function elementorExists(): bool
  {
    return class_exists('\ElementorPro\Modules\Forms\Classes\Action_Base');
  }
}
