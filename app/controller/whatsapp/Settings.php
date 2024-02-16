<?php

namespace Full\Customer\WhatsApp;

defined('ABSPATH') || exit;

class Settings
{
  const PREFIX = 'whatsapp-';

  public function set(string  $prop, $value): void
  {
    fullCustomer()->set(self::PREFIX . $prop, $value);
  }

  public function get(string  $prop)
  {
    return fullCustomer()->get(self::PREFIX . $prop);
  }

  public function getUrl(): string
  {
    return add_query_arg([
      'phone' => '55' . preg_replace('/\D/', '', $this->get('whatsappNumber')),
      'text'  => strip_tags($this->get('whatsappMessage')),
    ], 'https://api.whatsapp.com/send');
  }

  public function getLogoUrl(string $id = null): string
  {
    if (is_null($id)) :
      $id = $this->get('whatsappLogo');
    endif;

    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';
    return $baseUrl . 'img/whatsapp-logo/' . $id . '.png';
  }
}
