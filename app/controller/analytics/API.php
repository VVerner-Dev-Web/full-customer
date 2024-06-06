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

    add_action('wp_ajax_full/analytics/journey', [$cls, 'journey']);
    add_action('wp_ajax_full/analytics/journey/delete', [$cls, 'deleteJourney']);
  }

  public function deleteJourney(): void
  {
    $env = new Settings;
    $journeyId  = filter_input(INPUT_POST, 'journeyId');
    $journeys = $env->journeys();

    if (array_key_exists($journeyId, $journeys)) :
      unset($journeys[$journeyId]);
    endif;

    $env->set('journeys', $journeys);

    wp_send_json_success($env->journeys());
  }

  public function journey(): void
  {
    check_ajax_referer('full/analytics/journey');

    $env = new Settings;
    $journeyId  = filter_input(INPUT_POST, 'journeyId');

    if (!$journeyId) :
      $journeyId = strtoupper(uniqid());
    endif;

    $journeyName  = filter_input(INPUT_POST, 'journeyName');
    $journey    = array_filter(array_map('trim', filter_input(INPUT_POST, 'journey', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []));

    $journeys = $env->journeys();
    $journeys[$journeyId] = [
      'id' => $journeyId,
      'name' => $journeyName,
      'stages' => $journey
    ];

    $env->set('journeys', $journeys);

    wp_send_json_success($env->journeys());
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

    $labels = [];
    $sessions = [];
    $views = [];
    $journeys = [];

    foreach ((new Settings)->journeys() as $journey) {
      $journeys[$journey['id']] = $this->getJourneyStats($journey, $from, $to);
    }

    while ($to >= $from) {
      $labels[] = $from->format('d/m');
      $sessions[] = (int) $wpdb->get_var($wpdb->prepare($sessionsCountSql, $from->format('Y-m-d')));
      $views[] = (int) $wpdb->get_var($wpdb->prepare($viewsCountSql, $from->format('Y-m-d')));
      $from->modify('+1 day');
    }

    $sessionsSum = array_sum($sessions);
    $viewsSum = array_sum($views);

    wp_send_json([
      'totals' => [
        'sessions' => $sessionsSum,
        'views' => $viewsSum,
        'average' => $sessionsSum ? $viewsSum / $sessionsSum : 0
      ],
      'tables' => [
        'pages' => $wpdb->get_results($pagesTableSql),
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
      'journeysList' => (new Settings)->journeys(),
      'journeyStats' => $journeys
    ]);
  }

  private function getJourneyStats(array $journey, DateTime $from, DateTime $to): ?array
  {
    global $wpdb;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $response = [];
    $drop = "DROP TABLE IF EXISTS full_user_journeys";

    $wpdb->query($drop);

    $create = "CREATE TABLE full_user_journeys AS
      SELECT session, GROUP_CONCAT(page ORDER BY createdAt) AS journey
      FROM " .  Database::$table . "
      WHERE createdAt BETWEEN '{$from->format('Y-m-d')} 00:00:00' AND '{$to->format('Y-m-d')} 23:59:59'
      GROUP BY session;";

    ob_start();

    dbDelta($create);

    ob_clean();

    $where = [];

    foreach ($journey['stages'] as $stage) :
      $path  = parse_url($stage, PHP_URL_PATH);
      $where[] = trailingslashit($path);
      $value = "SELECT COUNT(*) FROM full_user_journeys WHERE journey LIKE '%" . implode(',', $where) . "%'";

      $response[] = [
        'name' => $path,
        'value' => (int) $wpdb->get_var($value),
      ];
    endforeach;

    $wpdb->query($drop);

    return $response ? $response : null;
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
