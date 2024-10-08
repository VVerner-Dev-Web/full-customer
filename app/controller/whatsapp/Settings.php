<?php

namespace Full\Customer\WhatsApp;

use stdClass;

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

  public function isButtonEnabled(): bool
  {
    return (int) $this->get('enableGlobalButton') === 1;
  }

  public function getSinglePostUrl($postId): string
  {
    $settings = $this->getSinglePostSettings($postId);

    return esc_url(add_query_arg([
      'phone' => '55' . preg_replace('/\D/', '', $settings->number ? $settings->number : $this->get('whatsappNumber')),
      'text'  => wp_strip_all_tags($settings->message ? $settings->message :  $this->get('whatsappMessage')),
    ], 'https://api.whatsapp.com/send'));
  }

  public function getUrl(): string
  {
    return esc_url(add_query_arg([
      'phone' => '55' . preg_replace('/\D/', '', $this->get('whatsappNumber')),
      'text'  => wp_strip_all_tags($this->get('whatsappMessage')),
    ], 'https://api.whatsapp.com/send'));
  }

  public function getLogoUrl(string $id = null): string
  {
    if (is_null($id)) :
      $id = $this->get('whatsappLogo');
    endif;

    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';
    return $baseUrl . 'img/whatsapp-logo/' . $id . '.png';
  }

  public function isButtonEnabledForGlobal(): bool
  {
    return $this->isButtonEnabled() && $this->get('displayCondition') === 'global';
  }

  public function isButtonEnabledForPostType($postType = null): bool
  {
    $keys = $this->get('validCpt');
    return $this->isButtonEnabledForGlobal() || ($this->isButtonEnabled() && is_array($keys) && in_array($postType, $keys, true));
  }

  public function getSinglePostSettings($postId): stdClass
  {
    return (object) [
      'display' => get_post_meta($postId, 'full/whatsappDisplay', true) ? get_post_meta($postId, 'full/whatsappDisplay', true) : 'inherit',
      'number' => get_post_meta($postId, 'full/whatsappNumber', true),
      'message' => get_post_meta($postId, 'full/whatsappMessage', true),
    ];
  }

  public function isButtonEnabledForSinglePost($postId): bool
  {
    $settings = $this->getSinglePostSettings($postId);
    return $settings->display === 'yes' || ($this->isButtonEnabledForPostType(get_post_type($postId)) && $settings->display === 'inherit');
  }
}
