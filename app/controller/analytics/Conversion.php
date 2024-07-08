<?php

namespace Full\Customer\Analytics;

use DateTime;

class Conversion
{
  public int $id;
  public string $name;
  public string $type;
  public string $element;
  public string $createdAt;

  public array $performance = [];

  public function __construct()
  {
  }

  public static function list(): array
  {
    global $wpdb;
    $items = $wpdb->get_results('SELECT * FROM ' . Database::$conversionTable);

    return array_map(function ($item) {
      $cls = new self();
      $cls->id = (int) $item->id;
      $cls->name = $item->name;
      $cls->type = $item->type;
      $cls->element = $item->element;
      $cls->createdAt = $item->createdAt;
      return $cls;
    }, $items);
  }

  public static function performance(array $list): array
  {
    return array_map([self::class, 'calculatePerformance'], $list);
  }

  private static function calculatePerformance(Conversion $item): Conversion
  {
    global $wpdb;

    $item->performance = [
      '7days' => [
        'trending' => 'up',
        'current' => 0,
        'change'  => 0
      ],
      '30days' => [
        'trending' => 'up',
        'current' => 0,
        'change'  => 0
      ],
      '90days' => [
        'trending' => 'up',
        'current' => 0,
        'change'  => 0
      ],
      'conversion' => 0
    ];

    $today = new DateTime(current_time('Y-m-d 00:00:00'));
    $last7days = (clone ($today))->modify('-7 days');
    $last30days = (clone ($today))->modify('-30 days');
    $last90days = (clone ($today))->modify('-90 days');
    $compareLast7days = (clone ($last7days))->modify('-7 days');
    $compareLast30days = (clone ($last30days))->modify('-30 days');
    $compareLast90days = (clone ($last90days))->modify('-90 days');

    $sql   = "SELECT COUNT(*) FROM " . Database::$conversionTrackerTable . " WHERE conversionId = %d AND createdAt BETWEEN %s AND %s";

    $item->performance['7days']['current']  = $wpdb->get_var($wpdb->prepare($sql, [$item->id, $last7days->format('Y-m-d H:i:s'), $today->format('Y-m-d 23:59:59')]));
    $item->performance['30days']['current'] = $wpdb->get_var($wpdb->prepare($sql, [$item->id, $last30days->format('Y-m-d H:i:s'), $today->format('Y-m-d 23:59:59')]));
    $item->performance['90days']['current'] = $wpdb->get_var($wpdb->prepare($sql, [$item->id, $last90days->format('Y-m-d H:i:s'), $today->format('Y-m-d 23:59:59')]));

    $change = $wpdb->get_var($wpdb->prepare($sql, [$item->id, $compareLast7days->format('Y-m-d H:i:s'), $last7days->format('Y-m-d 23:59:59')]));
    $item->performance['7days']['change'] = $change ? 100 * ($item->performance['7days']['current'] / $change - 1)  : 0;
    $change = $wpdb->get_var($wpdb->prepare($sql, [$item->id, $compareLast30days->format('Y-m-d H:i:s'), $last30days->format('Y-m-d 23:59:59')]));
    $item->performance['30days']['change'] = $change ? 100 * ($item->performance['30days']['current'] / $change - 1)  : 0;
    $change = $wpdb->get_var($wpdb->prepare($sql, [$item->id, $compareLast90days->format('Y-m-d H:i:s'), $last90days->format('Y-m-d 23:59:59')]));;
    $item->performance['90days']['change'] = $change ? 100 * ($item->performance['90days']['current'] / $change - 1)  : 0;

    $item->performance['7days']['trending'] = $item->performance['7days']['change'] > 0 ? 'up' : 'down';
    $item->performance['30days']['trending'] = $item->performance['30days']['change'] > 0 ? 'up' : 'down';
    $item->performance['90days']['trending'] = $item->performance['90days']['change'] > 0 ? 'up' : 'down';

    $item->performance['7days']['change'] = number_format_i18n($item->performance['7days']['change'], 1) . '%';
    $item->performance['30days']['change'] = number_format_i18n($item->performance['30days']['change'], 1) . '%';
    $item->performance['90days']['change'] = number_format_i18n($item->performance['90days']['change'], 1) . '%';

    $totalEvents = (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . Database::$conversionTrackerTable . ' WHERE conversionId = ' . $item->id);
    $totalSessions = (int) $wpdb->get_var('SELECT COUNT(DISTINCT(session)) FROM ' . Database::$table . ' WHERE createdAt >= "' . $item->createdAt . '"');

    $item->performance['conversion'] = $totalSessions ? number_format_i18n($totalEvents / $totalSessions, 1) . '%' : 0;

    return $item;
  }

  public static function delete(int $id): void
  {
    global $wpdb;
    $wpdb->delete(Database::$conversionTable, ['id' => $id]);
  }

  public function save(): void
  {
    global $wpdb;

    if (isset($this->id)) :
      $wpdb->update(
        Database::$conversionTable,
        [
          'type' => $this->type,
          'name' => $this->name,
          'element' => isset($this->element) ? $this->element : '',
          'createdAt' => isset($this->createdAt) ? $this->createdAt : current_time('Y-m-d H:i:s')
        ],
        [
          'id' => $this->id
        ],
        [
          '%s',
          '%s',
          '%s',
          '%s'
        ],
        [
          '%d'
        ]
      );
      return;
    endif;

    $wpdb->insert(
      Database::$conversionTable,
      [
        'type' => $this->type,
        'name' => $this->name,
        'element' => isset($this->element) ? $this->element : '',
        'createdAt' => isset($this->createdAt) ? $this->createdAt : current_time('Y-m-d H:i:s')
      ],
      [
        '%s',
        '%s',
        '%s',
        '%s'
      ]
    );
  }
}
