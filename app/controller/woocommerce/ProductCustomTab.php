<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class ProductCustomTab
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('enableProductCustomTab')) :
      return;
    endif;

    $cls = new self($env);
    add_filter('woocommerce_product_tabs', [$cls, 'registerTab']);
  }

  public function registerTab(array $tabs): array
  {
    $tabs['full_customer'] = [
      'title' => $this->env->get('customProductTabName') ? $this->env->get('customProductTabName') : 'FULL.',
      'priority' => 50,
      'callback' => [$this, 'tabContent']
    ];
    return $tabs;
  }

  public function tabContent(): void
  {
    do_action('full-customer/woocommerce/custom-product-tab-content');
    echo esc_html(apply_filters('the_content', $this->env->get('customProductTabContent')));
  }
}

ProductCustomTab::attach();
