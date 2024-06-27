<?php

namespace Full\Customer\SocialProof;

use WC_Order;

class WooCommerce
{
  private Settings $env;
  private string $feedFilename;
  private string $feedUrl;

  private function __construct()
  {
    $this->env = new Settings();
    $this->feedFilename = (untrailingslashit(WP_CONTENT_DIR)) . '/woocommerce-popup-orders-feed.json';
    $this->feedUrl = (untrailingslashit(WP_CONTENT_URL)) . '/woocommerce-popup-orders-feed.json';
  }

  public static function attach(): void
  {
    $cls = new self();

    if (!$cls->env->get('enableWooCommerceOrdersPopup')) :
      return;
    endif;

    add_action('init', [$cls, 'maybeCreateFeed']);
    add_action('woocommerce_new_order', [$cls, 'updateFeed']);

    add_action('wp_footer', [$cls, 'addPopup']);
    add_action('wp_enqueue_scripts', [$cls, 'enqueueScripts']);
  }

  public function maybeCreateFeed(): void
  {
    if (file_exists($this->feedFilename)) :
      return;
    endif;

    $data = [];

    $orders = wc_get_orders([
      'limit' => 10,
    ]);

    foreach ($orders as $order) :
      $data = array_merge($data, $this->extractOrderData($order));
    endforeach;

    file_put_contents($this->feedFilename, json_encode($data));
  }

  private function extractOrderData(WC_Order $order): array
  {
    $data = [];
    foreach ($order->get_items() as $item) :
      $product = $item->get_product();

      $data[] = [
        'product' => $product ? $product->get_name() : '',
        'image' => $product ? wp_get_attachment_url($product->get_image_id()) : '',
        'customerFirstName' => $order->get_billing_first_name(),
        'customerLastName' => $order->get_billing_last_name(),
        'customerLocation' => $order->get_billing_city() . '/' . $order->get_billing_state(),
        'orderDate' => $order->get_date_created()->format('d/m/Y'),
      ];
    endforeach;
    return $data;
  }

  public function updateFeed(int $orderId): void
  {
    $order = wc_get_order($orderId);

    $data = json_decode(file_get_contents($this->feedFilename));
    $data = array_merge($data, $this->extractOrderData($order));
    $data = array_slice($data, -20);

    file_put_contents($this->feedFilename, json_encode($data));
  }

  public function addPopup(): void
  {
    $position = $this->env->get('ordersPopupPosition');

    $template  = '<template id="full-woo-orders-popup-template">
    <div class="full-woo-orders-popup-inner"><div class="customer-information"><p>{name} {address} comprou <strong data-fragment="product"></strong> {orderDate}</p></div>{img}</div>
    </template>';

    $name = $this->env->fragmentEnabled('customerFirstName') ? '<span data-fragment="firstName"></span>' : '';
    $name .= $this->env->fragmentEnabled('customerLastName') ? ' <span data-fragment="lastName"></span>' : '';
    $address = $this->env->fragmentEnabled('customerLocation') ? ' de <span data-fragment="address"></span>' : '';
    $orderDate = $this->env->fragmentEnabled('orderDate') ? ' em <span data-fragment="orderDate"></span>' : '';
    $productThumbnail = $this->env->fragmentEnabled('productThumbnail') ? '<img src="" data-fragment="image">' : '';

    $template = str_replace('{name}', ($name ? $name : 'Alguém'), $template);
    $template = str_replace('{address}', ($address ? $address : ''), $template);
    $template = str_replace('{orderDate}', ($orderDate ? $orderDate : ''), $template);
    $template = str_replace('{img}', ($productThumbnail ? $productThumbnail : ''), $template);

    echo '<div id="full-woo-orders-popup" class="full-woo-orders-popup ' . $position . '">
      <span class="dismiss-woo-order-popup">&times;</span>
      <div class="full-woo-orders-popup-inner"></div>
    </div>';
    echo $template;
  }

  public function enqueueScripts(): void
  {
    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    wp_enqueue_style('full-social-proof', $baseUrl . 'css/social-proof.css', [], $version);
    wp_enqueue_script('full-social-proof', $baseUrl . 'js/social-proof.js', ['jquery'], $version, true);
    wp_localize_script('full-social-proof', 'socialProofFeed', add_query_arg('v', uniqid(), $this->feedUrl));
  }
}

WooCommerce::attach();
