<?php

namespace Full\Customer\Analytics;

class Database
{
  const VERSION = 11;

  public static string $table;

  private function __construct()
  {
  }

  public static function attach(): void
  {
    global $wpdb;

    $cls = new self();
    $cls::$table = $wpdb->prefix . 'full_page_views';

    add_action('init', [$cls, 'upgradeDb'], 0);
  }

  public function upgradeDb(): void
  {
    if (self::VERSION === (int) get_option('full/analytics/db-version', 0)) :
      return;
    endif;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta("CREATE TABLE `" . Database::$table . "` (
      `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , 
      `session` VARCHAR(50) NOT NULL , 
      `createdAt` DATETIME NOT NULL , 
      `page` TEXT NOT NULL , 
      `queryString` TEXT NOT NULL , 
      PRIMARY KEY (`id`), 
      INDEX (`session`)
    ) ENGINE = InnoDB;");

    update_option('full/analytics/db-version', self::VERSION, false);
  }
}

Database::attach();
