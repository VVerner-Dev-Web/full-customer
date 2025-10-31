<?php

namespace Full\Customer\SecretCoupon;

use \WC_Coupon;

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

    if (!$cls->env->get('enabled')) :
      return;
    endif;

    add_action('woocommerce_before_cart_table', [$cls, 'displayWarnings']);
    add_action('woocommerce_before_mini_cart_contents', [$cls, 'displayWarnings']);

    add_action('wp_enqueue_scripts', [$cls, 'enqueueScripts']);

    add_filter('woocommerce_get_shop_coupon_data', [$cls, 'updateCoupon'], PHP_INT_MAX, 2);
    add_filter('woocommerce_coupon_is_valid', [$cls, 'validateCoupon'], PHP_INT_MAX, 2);

    add_action('woocommerce_before_cart', [$cls, 'maybeApplyCoupon']);
  }

  public function validateCoupon(bool $valid, WC_Coupon $coupon): bool
  {
    if (!$valid || $coupon->get_code() !== self::COUPON) :
      return $valid;
    endif;

    return true;
  }

  public function maybeApplyCoupon(): void
  {
    if (WC()->cart->has_discount(self::COUPON) || $this->getMissingAmount()) {
      return;
    }

    WC()->cart->apply_coupon(self::COUPON);
  }

  public function updateCoupon($coupon, string $code)
  {
    if ($code !== self::COUPON) :
      return $coupon;
    endif;

    return [
      'code' => self::COUPON,
      'description' => 'Cupom secreto!',
      'individual_use' => false,
      'discount_type' => $this->env->get('discountType'),
      'amount' => (float) $this->env->get('couponAmount'),
      'free_shipping' => (bool) $this->env->get('freeShipping'),
      'minimum_amount' => (float) $this->env->get('minimumAmount'),
      'is_virtual' => true,
    ];
  }

  public function enqueueScripts(): void
  {
    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    wp_enqueue_style('full-secret-coupon', $baseUrl . 'css/secret-coupon.css', [], $version);
  }

  public function displayWarnings(): void
  {
    $minAmount = (float) $this->env->get('minimumAmount');
    $missingAmount = $this->getMissingAmount();

    $message = $missingAmount ? $this->env->get('warningMessage') : $this->env->get('appliedMessage');
    $alertClass = $missingAmount ? 'coupon-pending' : 'coupon-applied';
    $message = str_replace(['{valorMinimo}', '{valorFaltante}'], [wc_price($minAmount), wc_price($missingAmount)], $message);

    echo '<div id="full-cart-secret-coupon" class="woocommerce-message ' . $alertClass . '" role="alert">' . $message . '</div>';
  }

  private function getMissingAmount(): float
  {
    $cartAmount = WC()->cart->subtotal;

    $minAmount = (float) $this->env->get('minimumAmount');
    return max($minAmount - $cartAmount, 0);
  }
}

Frontend::attach();
