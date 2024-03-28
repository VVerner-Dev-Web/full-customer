<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class TestPaymentGateway
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('enableTestPaymentGateway')) :
      return;
    endif;

    $cls = new self($env);
    add_filter('woocommerce_payment_gateways', [$cls, 'enqueueGateway']);
    add_action('init', [$cls, 'loadGatewayClass']);
  }

  public function enqueueGateway(array $gateways): array
  {
    $gateways[] = 'FullCustomerTestGateway';
    return $gateways;
  }

  public function loadGatewayClass(): void
  {
    require_once __DIR__ . '/FullCustomerTestGateway.php';
  }
}

TestPaymentGateway::attach();
