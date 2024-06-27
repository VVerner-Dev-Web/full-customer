<?php

namespace Full\Customer\SocialProof;

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
    add_action('wp_ajax_full/widget/social-proof', [$cls, 'processSettings']);
  }

  public function addMenuPages(): void
  {
    add_submenu_page(
      'full-connection',
      'FULL.SocialProof',
      'FULL.SocialProof',
      'edit_posts',
      'full-social-proof',
      'fullGetAdminPageView'
    );
  }

  public function enqueueScripts(): void
  {
    if ('social-proof' !== fullAdminPageEndpoint()) :
      return;
    endif;

    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    wp_enqueue_script('full-admin-social-proof', $baseUrl . 'js/admin-social-proof.js', ['jquery'], $version, true);
  }

  public function processSettings(): void
  {
    check_ajax_referer('full/widget/social-proof');
    $env = new Settings();

    $env->set('enableWooCommerceOrdersPopup', filter_input(INPUT_POST, 'enableWooCommerceOrdersPopup', FILTER_VALIDATE_BOOL));
    $env->set('ordersPopupPosition', filter_input(INPUT_POST, 'ordersPopupPosition'));
    $env->set('ordersPopupFragments', filter_input(INPUT_POST, 'ordersPopupFragments', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY));

    wp_send_json_success();
  }
}

Admin::attach();
