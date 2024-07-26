<?php

namespace Full\Customer\Analytics;

class Admin
{
  const CRON_JOB = 'full/analytics/delete-expired';

  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_filter('full-customer/active-widgets-menu', [$cls, 'addMenuPages'], 150);
    add_action('wp', [$cls, 'registerCronJob']);
    add_action(self::CRON_JOB, [$cls, 'cron']);
  }


  public function registerCronJob(): void
  {
    if (!wp_next_scheduled(self::CRON_JOB)) {
      wp_schedule_event(current_time('timestamp'), 'daily', self::CRON_JOB);
    }
  }

  public function cron(): void
  {
    global $wpdb;
    $trackingPeriod = (int) (new Settings)->get('trackingPeriod');

    if (!$trackingPeriod) :
      return;
    endif;

    $wpdb->query(
      $wpdb->prepare(
        "DELETE FROM `" . Database::$table . "` WHERE DATE(created) <= SUBDATE(%s, %d)",
        current_time('Y-m-d'),
        $trackingPeriod
      )
    );

    $wpdb->query("OPTIMIZE TABLE `" . Database::$table . "`");
  }

  public function addMenuPages(array $menu): array
  {
    $menu[] = [
      'name' => 'FULL.analytics',
      'endpoint' => 'full-analytics',
    ];

    return $menu;
  }
}

Admin::attach();
