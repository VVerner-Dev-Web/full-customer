<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class OrderReceived
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('orderReceivedPageCustomCode') || trim($env->get('orderReceivedPageCustomCode')) === '' || trim($env->get('orderReceivedPageCustomCode')) === '0') :
      return;
    endif;

    $cls = new self($env);
    add_action('woocommerce_thankyou', [$cls, 'insertCode'], 100);
  }

  public function insertCode(): void
  {
    echo esc_html($this->env->get('orderReceivedPageCustomCode'));
  }
}

OrderReceived::attach();
