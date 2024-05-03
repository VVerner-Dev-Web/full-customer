<?php

class FullCustomerTestGateway extends WC_Payment_Gateway
{
  public function __construct()
  {
    $this->id = 'full-customer';
    $this->icon = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/img/menu-novo.png';
    $this->has_fields = false;
    $this->method_title = 'FULL. Pagamento teste';
    $this->method_description = 'Método de pagamento de testes da FULL.';

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
        'title'       => 'Title',
        'type'        => 'text',
        'default'     => 'Pagamento teste',
        'desc_tip'    => true,
      ],
      'description' => [
        'title'       => 'Description',
        'type'        => 'textarea',
        'default'     => 'Pagamento teste criado pela FULL.',
      ],
    ];
  }

  public function process_payment($orderId)
  {
    $order = wc_get_order($orderId);

    $order->payment_complete();
    $order->reduce_order_stock();

    $order->add_order_note('Pagamento teste realizado com sucesso.');

    WC()->cart->empty_cart();

    return [
      'result' => 'success',
      'redirect' => $this->get_return_url($order),
    ];
  }
}
