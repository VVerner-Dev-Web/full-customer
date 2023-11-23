<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class OrderReceived
{
  public $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!trim($env->get('orderReceivedPageCustomCode'))) :
      return;
    endif;

    $cls = new self($env);
    add_action('woocommerce_thankyou', [$cls, 'insertCode'], 100);
  }

  public function insertCode(): void
  {
    echo $this->env->get('orderReceivedPageCustomCode');
  }
}

OrderReceived::attach();
