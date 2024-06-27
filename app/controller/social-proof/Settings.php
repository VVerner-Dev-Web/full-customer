<?php

namespace Full\Customer\SocialProof;

defined('ABSPATH') || exit;

class Settings
{
  const PREFIX = 'social-proof-';

  public function set(string  $prop, $value): void
  {
    fullCustomer()->set(self::PREFIX . $prop, $value);
  }

  public function get(string  $prop)
  {
    return fullCustomer()->get(self::PREFIX . $prop);
  }

  public function fragmentEnabled(string $fragment): bool
  {
    $enabled = $this->get('ordersPopupFragments');
    return is_array($enabled) && in_array($fragment, $enabled, true);
  }
}
