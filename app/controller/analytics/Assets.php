<?php

namespace Full\Customer\Analytics;

class Assets
{
  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_action('wp_enqueue_scripts', [$cls, 'site'], 0);
    add_action('admin_enqueue_scripts', [$cls, 'admin'], 0);
  }

  public function site(): void
  {
    wp_enqueue_script('full-analytics', plugin_dir_url(FULL_CUSTOMER_FILE) . 'app/assets/js/full-analytics.js', [], getFullAssetsVersion(), true);
    wp_localize_script('full-analytics', 'fullAnalytics', [
      'conversions'   => Conversion::list(),
      'conversionEndpoint' => add_query_arg([
        'action'    => 'full/track-conversion',
        'nonce'     => wp_create_nonce('full/track-conversion'),
      ], admin_url('admin-ajax.php')),
      'timeoutWindow' => MINUTE_IN_SECONDS * 5 * 1000,
      'endpoint' => add_query_arg([
        'action'    => 'full/track',
        'nonce'     => wp_create_nonce('full/track'),
      ], admin_url('admin-ajax.php')),
    ]);
  }

  public function admin(): void
  {
    if ('analytics' !== fullAdminPageEndpoint()) :
      return;
    endif;

    wp_enqueue_style('full-analytics', plugin_dir_url(FULL_CUSTOMER_FILE) . 'app/assets/css/analytics.css', [], getFullAssetsVersion());

    wp_enqueue_script('full-chartjs', plugin_dir_url(FULL_CUSTOMER_FILE) . 'app/assets/vendor/chartjs/app.js', [], '4.4.1', true);
    wp_enqueue_script('full-litepicker', plugin_dir_url(FULL_CUSTOMER_FILE) . 'app/assets/vendor/litepicker/app.js', [], '2.0.12', true);
    wp_enqueue_script('full-analytics', plugin_dir_url(FULL_CUSTOMER_FILE) . 'app/assets/js/admin-analytics.js', [], getFullAssetsVersion(), true);
  }
}

Assets::attach();
