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

    add_filter('full-customer/active-widgets-menu', [$cls, 'addMenuPages'], 150);
    add_action('admin_enqueue_scripts', [$cls, 'enqueueScripts'], 150);
    add_action('wp_ajax_full/widget/social-proof/purchases', [$cls, 'purchasesAjaxCallback']);
    add_action('wp_ajax_full/widget/social-proof/visitors', [$cls, 'visitorsAjaxCallback']);
  }

  public function addMenuPages(array $menu): array
  {
    $menu[] = [
      'name' => 'FULL.social proof',
      'endpoint' => 'full-social-proof'
    ];

    return $menu;
  }

  public function enqueueScripts(): void
  {
    if ('social-proof' !== fullAdminPageEndpoint()) :
      return;
    endif;

    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    wp_enqueue_style('full-select2', $baseUrl . 'vendor/select2/select2.min.css', [], '4.1.0');
    wp_enqueue_script('full-select2', $baseUrl . 'vendor/select2/select2.min.js', ['jquery'], '4.1.0', true);

    wp_enqueue_script('full-admin-social-proof', $baseUrl . 'js/admin-social-proof.js', ['jquery'], $version, true);
  }

  public function purchasesAjaxCallback(): void
  {
    check_ajax_referer('full/widget/social-proof/purchases');
    $env = new Settings();

    $env->set('excludedPages', filter_input(INPUT_POST, 'excludedPages', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY));
    $env->set('enableWooCommerceOrdersPopup', filter_input(INPUT_POST, 'enableWooCommerceOrdersPopup', FILTER_VALIDATE_BOOL));
    $env->set('ordersPopupPosition', filter_input(INPUT_POST, 'ordersPopupPosition'));
    $env->set('ordersPopupFragments', filter_input(INPUT_POST, 'ordersPopupFragments', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY));

    wp_send_json_success();
  }

  public function visitorsAjaxCallback(): void
  {
    check_ajax_referer('full/widget/social-proof/visitors');
    $env = new Settings();

    $env->set('visitorsEnabledOn', filter_input(INPUT_POST, 'visitorsEnabledOn', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY));
    $env->set('visitorTrackingWindow', filter_input(INPUT_POST, 'visitorTrackingWindow', FILTER_VALIDATE_INT));
    $env->set('visitorsPopupPosition', filter_input(INPUT_POST, 'visitorsPopupPosition'));

    wp_send_json_success();
  }
}

Admin::attach();
