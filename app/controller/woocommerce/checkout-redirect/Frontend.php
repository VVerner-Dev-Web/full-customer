<?php

namespace Full\Customer\CheckoutRedirect;

class Frontend
{
  private Settings $env;

  const COUPON = 'cupomsecreto';

  private function __construct()
  {
    $this->env = new Settings();
  }

  public static function attach(): void
  {
    $cls = new self();

    add_action('woocommerce_thankyou', [$cls, 'redirect']);
  }

  public function redirect(int $orderId): void
  {
    $order = wc_get_order($orderId);

    $key = '';

    if ($order->has_status(['processing', 'completed'])) {
      $key = 'success';
    } elseif ($order->has_status(['cancelled', 'refunded', 'failed'])) {
      $key = 'error';
    } elseif ($order->has_status(['pending', 'on-hold'])) {
      $key = 'pending';
    }

    $redirectUrl = $this->env->get($key . 'Redirect');

    foreach ($order->get_items() as $item) :
      $productUrl = get_post_meta($item->get_product_id(), 'full_' . $key . '_checkout_redirect', true);

      if ($productUrl) :
        $redirectUrl = $productUrl;
      endif;
    endforeach;

    if ($redirectUrl) :
      wp_safe_redirect(esc_url(add_query_arg('orderId', $order->get_id(), $redirectUrl)));
      exit;
    endif;
  }
}

Frontend::attach();
