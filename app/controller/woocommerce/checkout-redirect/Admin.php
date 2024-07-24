<?php

namespace Full\Customer\CheckoutRedirect;

class Admin
{
  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_action('wp_ajax_full/widget/checkout-redirect', [$cls, 'ajaxCallback']);

    add_action('woocommerce_product_options_general_product_data', [$cls, 'addProductFields']);
    add_action('woocommerce_process_product_meta', [$cls, 'processProductFields']);
  }

  public function ajaxCallback(): void
  {
    check_ajax_referer('full/widget/checkout-redirect');
    $env = new Settings();

    $env->set('successRedirect', esc_url_raw(filter_input(INPUT_POST, 'successRedirect', FILTER_VALIDATE_URL) ?? ''));
    $env->set('errorRedirect', esc_url_raw(filter_input(INPUT_POST, 'errorRedirect', FILTER_VALIDATE_URL) ?? ''));
    $env->set('pendingRedirect', esc_url_raw(filter_input(INPUT_POST, 'pendingRedirect', FILTER_VALIDATE_URL) ?? ''));

    wp_send_json_success();
  }

  public function addProductFields(): void
  {
    echo '<div class="options_group">';

    echo '<h4 style="margin-left: 10px">Regras de redirecionamento</h4>';

    woocommerce_wp_text_input([
      'id'          => 'full_success_checkout_redirect',
      'label'       => 'Pagamentos bem sucedidos',
      'type'        => 'url',
      'desc_tip'    => true,
      'description' => 'Pedidos com status: Processando ou Concluído'
    ]);

    woocommerce_wp_text_input([
      'id'          => 'full_error_checkout_redirect',
      'label'       => 'Pagamentos com erro',
      'type'        => 'url',
      'desc_tip'    => true,
      'description' => 'Pedidos com status: Malsucedido, Cancelado ou Reembolsado'
    ]);

    woocommerce_wp_text_input([
      'id'          => 'full_pending_checkout_redirect',
      'label'       => 'Pagamentos pendentes',
      'type'        => 'url',
      'desc_tip'    => true,
      'description' => 'Pedidos com status: Aguardando ou Pagamento pendente'
    ]);

    echo '</div>';
  }

  public function processProductFields(int $productId): void
  {
    update_post_meta($productId, 'full_success_checkout_redirect', esc_url_raw(filter_input(INPUT_POST, 'full_success_checkout_redirect', FILTER_VALIDATE_URL) ?? ''));
    update_post_meta($productId, 'full_error_checkout_redirect', esc_url_raw(filter_input(INPUT_POST, 'full_error_checkout_redirect', FILTER_VALIDATE_URL) ?? ''));
    update_post_meta($productId, 'full_pending_checkout_redirect', esc_url_raw(filter_input(INPUT_POST, 'full_pending_checkout_redirect', FILTER_VALIDATE_URL) ?? ''));
  }
}

Admin::attach();
