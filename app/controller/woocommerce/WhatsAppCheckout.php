<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class WhatsAppCheckout
{
  public $env;
  public string $phoneNumber;
  public string $template;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('enableWhatsAppCheckout')) :
      return;
    endif;

    $cls = new self($env);
    $cls->phoneNumber = '55' . preg_replace('/\D/', '', (string) $cls->env->get('whatsAppCheckoutNumber'));
    $cls->template = (string) $cls->env->get('whatsAppCheckoutMessage');

    add_action('template_redirect', [$cls, 'redirectCheckout'], PHP_INT_MAX);
    add_filter('wc_get_template', [$cls, 'updateButtonText'], PHP_INT_MAX, 2);
  }

  public function updateButtonText(string $file, string $template): string
  {
    if ('cart/proceed-to-checkout-button.php' === $template) :
      $file = FULL_CUSTOMER_APP . '/views/woocommerce/proceed-to-checkout-button.php';
    endif;

    return $file;
  }

  public function redirectCheckout(): void
  {
    if (!is_checkout()) :
      return;
    endif;

    $cartItems = '';
    $total     = '*R$' . number_format_i18n(WC()->cart->get_total('edit'), 2) . '*';

    foreach (WC()->cart->get_cart() as $item) :
      $cartItems .= '- ' . $item['quantity'] . 'x ' . $item['data']->get_name() . PHP_EOL;
    endforeach;

    $message = str_replace(
      ['{itens_do_carrinho}', '{preco_total_carrinho}', PHP_EOL],
      [$cartItems, $total, '%0a'],
      $this->template
    );

    $url = 'https://wa.me/' . $this->phoneNumber . '?text=' . $message;

    header('Location: ' . $url);
    exit;
  }
}

WhatsAppCheckout::attach();
