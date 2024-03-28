<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class AutocompleteOrders
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('autocompleteProcessingOrders')) :
      return;
    endif;

    $cls = new self($env);
    add_action('woocommerce_order_status_processing', [$cls, 'changeOrderStatus']);
  }

  public function changeOrderStatus(int $orderId): void
  {
    $order = wc_get_order($orderId);
    $order->set_status('completed', 'Status atualizado automaticamente pela FULL.');
    $order->save();
  }
}

AutocompleteOrders::attach();
