<?php

class FullCustomerEstimateGateway extends WC_Payment_Gateway
{
  public function __construct()
  {
    $this->id = 'full-customer-estimate';
    $this->icon = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/img/menu-novo.png';
    $this->has_fields = false;
    $this->method_title = 'FULL. Solicitar orçamento';
    $this->method_description = 'Método de pagamento da FULL. que indica solicitação de orçamento';

    $this->supports = ['products'];

    $this->init_form_fields();

    $this->init_settings();
    $this->title = $this->get_option('title');
    $this->description = $this->get_option('description');
    $this->enabled = 'yes';

    add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
  }

  public function init_form_fields(): void
  {
    $this->form_fields = [
      'title' => [
        'title'       => 'Titulo',
        'type'        => 'text',
        'default'     => 'Solicitar orçamento',
        'desc_tip'    => true,
      ],
      'description' => [
        'title'       => 'Descrição',
        'type'        => 'textarea',
        'default'     => '',
      ],
    ];
  }

  public function process_payment($orderId)
  {
    $order = wc_get_order($orderId);

    $order->set_status('full-estimate');
    $order->save();

    WC()->cart->empty_cart();

    return [
      'result' => 'success',
      'redirect' => $this->get_return_url($order),
    ];
  }
}
