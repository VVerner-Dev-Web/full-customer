<?php

namespace Full\Customer\Analytics;

use DateTime;

class API
{
  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_action('wp_ajax_full/track', [$cls, 'track']);
    add_action('wp_ajax_nopriv_full/track', [$cls, 'track']);

    add_action('wp_ajax_full/analytics/settings', [$cls, 'updateSettings']);
    add_action('wp_ajax_full/analytics/report', [$cls, 'report']);
  }

  public function track(): void
  {
    if (!wp_verify_nonce(filter_input(INPUT_GET, 'nonce') ?? '', 'full/track')) :
      wp_send_json_error();
    endif;

    if (is_user_logged_in() && !(new Settings)->get('trackingUsers')) :
      wp_send_json_success();
    endif;

    $pv = new PageView;
    $pv->page = sanitize_text_field(filter_input(INPUT_POST, 'page'));
    $pv->session = sanitize_text_field(filter_input(INPUT_POST, 'session'));
    $pv->queryString = sanitize_text_field(filter_input(INPUT_POST, 'queryString'));
    $pv->save();

    wp_send_json_success();
  }

  public function report(): void
  {
    global $wpdb;
    check_ajax_referer('full/analytics/report');
    $period = array_filter(array_map('trim', explode('-', filter_input(INPUT_POST, 'period') ?? '')));

    if (count($period) !== 2) :
      wp_send_json_error();
    endif;

    $from = DateTime::createFromFormat('d/m/Y', $period[0]);
    $to = DateTime::createFromFormat('d/m/Y', $period[1]);

    $sessionsCountSql = "SELECT COUNT(DISTINCT session) FROM " . Database::$table . " WHERE DATE(createdAt) = %s GROUP BY DATE(createdAt);";
    $viewsCountSql = "SELECT COUNT(id) FROM " . Database::$table . " WHERE DATE(createdAt) = %s GROUP BY DATE(createdAt);";
    $pagesTableSql = "SELECT `page` as item, COUNT(id) as entries FROM " . Database::$table . " GROUP BY `page` ORDER BY entries DESC LIMIT 0,10;";
    $qsTableSql = "SELECT `queryString` as item, COUNT(id) as entries FROM " . Database::$table . " GROUP BY `queryString` ORDER BY queryString DESC LIMIT 0,10;";

    $labels = [];
    $sessions = [];
    $views = [];

    while ($to >= $from) {
      $labels[] = $from->format('d/m');
      $sessions[] = (int) $wpdb->get_var($wpdb->prepare($sessionsCountSql, $from->format('Y-m-d')));
      $views[] = (int) $wpdb->get_var($wpdb->prepare($viewsCountSql, $from->format('Y-m-d')));
      $from->modify('+1 day');
    }

    $sessions = array_sum($sessions);
    $views = array_sum($views);

    wp_send_json([
      'totals' => [
        'sessions' => $sessions,
        'views' => $views,
        'average' => $sessions ? $views / $sessions : 0
      ],
      'tables' => [
        'pages' => $wpdb->get_results($pagesTableSql),
        'queryStrings' => $wpdb->get_results($qsTableSql),
      ],
      'chartData' => [
        'labels' => $labels,
        'datasets' => [
          [
            'label' => 'Páginas',
            'data'  => $views,
            'borderColor' => "rgba(0,201,167,1)",
            'backgroundColor' => "rgba(0,201,167,.5)",
            'order' => 1,
          ],
          [
            'type'  => 'line',
            'label' => 'Sessões',
            'data'  => $sessions,
            'borderColor' => "rgba(55,125,255,1)",
            'backgroundColor' => "rgba(55,125,255,.5)",
            'order' => 1,
          ]
        ]
      ],
    ]);
  }

  public function updateSettings(): void
  {
    check_ajax_referer('full/analytics/settings');

    $env = new Settings;
    $env->set('trackingUsers', filter_input(INPUT_POST, 'trackingUsers', FILTER_VALIDATE_BOOL));
    $env->set('trackingPeriod', filter_input(INPUT_POST, 'trackingPeriod', FILTER_VALIDATE_INT));

    wp_send_json_success();
  }
}

API::attach();
