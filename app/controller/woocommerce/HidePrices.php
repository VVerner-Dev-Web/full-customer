<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class HidePrices
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('hidePrices')) :
      return;
    endif;

    $cls = new self($env);
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('flatsome_single_product_lightbox_summary', 'woocommerce_template_single_price', 10);
    add_action('wp_enqueue_scripts', [$cls, 'assets']);
  }

  public function assets(): void
  {
    $assetsUrl  = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';
    $version    = getFullAssetsVersion();

    wp_enqueue_style('full-woocommerce-estimate', $assetsUrl . 'css/woocommerce.css', [], $version);
  }
}

HidePrices::attach();
