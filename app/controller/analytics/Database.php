<?php

namespace Full\Customer\Analytics;

class Database
{
  const VERSION = 20;

  public static string $table;
  public static string $conversionTable;
  public static string $conversionTrackerTable;

  private function __construct()
  {
  }

  public static function attach(): void
  {
    global $wpdb;

    $cls = new self();
    $cls::$table = $wpdb->prefix . 'full_page_views';
    $cls::$conversionTable = $wpdb->prefix . 'full_conversions';
    $cls::$conversionTrackerTable = $wpdb->prefix . 'full_conversions_tracker';

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

    dbDelta("CREATE TABLE `" . Database::$conversionTable . "` (
      `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , 
      `name` TEXT NOT NULL , 
      `type` VARCHAR(50) NOT NULL , 
      `createdAt` DATETIME NOT NULL , 
      `element` TEXT NOT NULL , 
      PRIMARY KEY (`id`)
    ) ENGINE = InnoDB;");

    dbDelta("CREATE TABLE `" . Database::$conversionTrackerTable . "` (
      `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , 
      `conversionId` BIGINT UNSIGNED NOT NULL NOT NULL ,
      `createdAt` DATETIME NOT NULL , 
      PRIMARY KEY (`id`), 
      INDEX (`conversionId`)
    ) ENGINE = InnoDB;");

    update_option('full/analytics/db-version', self::VERSION, false);
  }
}

Database::attach();
