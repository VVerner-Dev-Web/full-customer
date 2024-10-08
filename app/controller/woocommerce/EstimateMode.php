<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class EstimateMode
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('enableEstimateOrders')) :
      return;
    endif;

    $cls = new self($env);
    add_action('init', [$cls, 'loadGatewayClass']);
    add_action('init', [$cls, 'registerOrderStatus']);

    add_filter('woocommerce_payment_gateways', [$cls, 'enqueueGateway']);
    add_action('wc_order_statuses', [$cls, 'enqueueOrderStatus']);
  }

  public function enqueueGateway(array $gateways): array
  {
    $gateways[] = 'FullCustomerEstimateGateway';
    return $gateways;
  }

  public function loadGatewayClass(): void
  {
    require_once __DIR__ . '/FullCustomerEstimateGateway.php';
  }

  public function registerOrderStatus(): void
  {
    /* translators: %s is replaced with the number of orders */
    $label = _n_noop('Aguardando orçamento (%s)', 'Aguardando orçamentos (%s)');

    register_post_status('wc-full-estimate', [
      'label'    => 'Aguardando orçamento',
      'public'  => true,
      'show_in_admin_status_list' => true,
      'label_count'  => $label
    ]);
  }

  public function enqueueOrderStatus(array $status): array
  {
    $status['wc-full-estimate'] = 'Aguardando orçamento';
    return $status;
  }
}

EstimateMode::attach();
