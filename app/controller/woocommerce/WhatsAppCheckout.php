<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

class WhatsAppCheckout
{

  public Settings $env;
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

    global $is_IIS;

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

    $location = str_replace(' ', '%20', $url);

    $regex    = '/
    (
      (?: [\xC2-\xDF][\x80-\xBF]        # double-byte sequences   110xxxxx 10xxxxxx
      |   \xE0[\xA0-\xBF][\x80-\xBF]    # triple-byte sequences   1110xxxx 10xxxxxx * 2
      |   [\xE1-\xEC][\x80-\xBF]{2}
      |   \xED[\x80-\x9F][\x80-\xBF]
      |   [\xEE-\xEF][\x80-\xBF]{2}
      |   \xF0[\x90-\xBF][\x80-\xBF]{2} # four-byte sequences   11110xxx 10xxxxxx * 3
      |   [\xF1-\xF3][\x80-\xBF]{3}
      |   \xF4[\x80-\x8F][\x80-\xBF]{2}
    ){1,40}                              # ...one or more times
    )/x';
    $location = preg_replace_callback($regex, '_wp_sanitize_utf8_in_redirect', $location);
    $location = preg_replace('|[^a-z0-9-~+_.?#=&;,/:%!*\[\]()@]|i', '', $location);
    $location = wp_kses_no_null($location);

    if (!$is_IIS && 'cgi-fcgi' !== PHP_SAPI) {
      status_header(302);
    }

    header("X-Redirect-By: FULL. WhatsApp Checkout");
    header("Location: $location", true, 302);
    exit;
  }
}

WhatsAppCheckout::attach();
