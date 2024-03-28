<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class ProductReviews
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('disableProductReviews')) :
      return;
    endif;

    $cls = new self($env);

    remove_filter('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    remove_filter('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    add_filter('woocommerce_product_tabs', [$cls, 'removeTab'], 98);
  }

  function removeTab(array $tabs): array
  {
    unset($tabs['reviews']);
    return $tabs;
  }
}

ProductReviews::attach();
