<?php

namespace Full\Customer\Email;

defined('ABSPATH') || exit;

class Settings
{
  const PREFIX = 'email-';

  public function set(string  $prop, $value): void
  {
    fullCustomer()->set(self::PREFIX . $prop, $value);
  }

  public function get(string  $prop)
  {
    return fullCustomer()->get(self::PREFIX . $prop);
  }

  public function settingsComplete(): bool
  {
    return
      $this->get('smtpHost') &&
      $this->get('smtpPort') &&
      $this->get('smtpSecurity') &&
      $this->get('smtpUser') &&
      $this->get('smtpPassword');
  }
}
