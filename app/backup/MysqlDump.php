<?php

namespace Full\Customer\Backup;

use Rah\Danpu\Dump;
use Rah\Danpu\Export;
use Rah\Danpu\Import;
use Exception;
use PDO;

class MysqlDump
{
  public function export(string $file): void
  {
    error_reporting(error_reporting() & ~E_NOTICE);

    try {
      $dump = new Dump;
      $dump
        ->file($file)
        ->dsn('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME)
        ->user(DB_USER)
        ->pass(DB_PASSWORD)
        ->prefix('bkp_')
        ->disableUniqueKeyChecks(true)
        ->disableForeignKeyChecks(true);

      new Export($dump);
    } catch (Exception $e) {
      error_log('Export failed with message: ' . $e->getMessage());
    }
  }

  public function import(string $file): void
  {
    error_reporting(error_reporting() & ~E_NOTICE);

    try {
      $dump = new Dump;
      $dump
        ->file($file)
        ->dsn('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME)
        ->user(DB_USER)
        ->pass(DB_PASSWORD)
        ->disableUniqueKeyChecks(true)
        ->disableForeignKeyChecks(true)
        ->attributes([
          PDO::ATTR_ORACLE_NULLS             => PDO::NULL_NATURAL,
          PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false,
          PDO::ATTR_ERRMODE                  => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_EMULATE_PREPARES         => false,
          PDO::ATTR_STRINGIFY_FETCHES        => false,
        ]);

      new Import($dump);
    } catch (Exception $e) {
      error_log('Import failed with message: ' . $e->getMessage());
    }
  }
}
