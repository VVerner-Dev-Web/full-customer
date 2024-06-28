<?php

namespace Full\Customer\SecretCoupon;

class Admin
{
  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_action('admin_menu', [$cls, 'addMenuPages'], 150);
    add_action('admin_enqueue_scripts', [$cls, 'enqueueScripts'], 150);
    add_action('wp_ajax_full/widget/secret-coupon', [$cls, 'ajaxCallback']);
  }

  public function addMenuPages(): void
  {
    add_submenu_page(
      'full-connection',
      'FULL.Cupom Secreto',
      'FULL.Cupom Secreto',
      'edit_posts',
      'full-secret-coupon',
      'fullGetAdminPageView'
    );
  }

  public function enqueueScripts(): void
  {
    if ('secret-coupon' !== fullAdminPageEndpoint()) :
      return;
    endif;

    // $version = getFullAssetsVersion();
    // $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    // wp_enqueue_script('full-admin-social-proof', $baseUrl . 'js/admin-social-proof.js', ['jquery'], $version, true);
  }

  public function ajaxCallback(): void
  {
    check_ajax_referer('full/widget/secret-coupon');
    $env = new Settings();

    $env->set('enabled', filter_input(INPUT_POST, 'enabled', FILTER_VALIDATE_BOOL));
    $env->set('warningMessage', sanitize_text_field(filter_input(INPUT_POST, 'warningMessage')));
    $env->set('appliedMessage', sanitize_text_field(filter_input(INPUT_POST, 'appliedMessage')));
    $env->set('minimumAmount', filter_input(INPUT_POST, 'minimumAmount', FILTER_VALIDATE_FLOAT));
    $env->set('couponAmount', filter_input(INPUT_POST, 'couponAmount', FILTER_VALIDATE_FLOAT));
    $env->set('discountType', filter_input(INPUT_POST, 'discountType') === 'fixed_cart' ? 'fixed_cart' : 'percent');
    $env->set('freeShipping', filter_input(INPUT_POST, 'freeShipping', FILTER_VALIDATE_BOOL));

    wp_send_json_success();
  }
}

Admin::attach();
