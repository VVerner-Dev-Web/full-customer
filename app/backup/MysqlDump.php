<?php

namespace Full\Customer\Backup;

use Rah\Danpu\Dump;
use Rah\Danpu\Export;
use Rah\Danpu\Import;
use Exception;

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
        ->disableUniqueKeyChecks(true)
        ->disableForeignKeyChecks(true);

      new Export($dump);

      $this->updateZeroDates($file);
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
        ->disableForeignKeyChecks(true);

      new Import($dump);
    } catch (Exception $e) {
      error_log('Import failed with message: ' . $e->getMessage());
    }
  }

  private function updateZeroDates($file): void
  {
    $content = file_get_contents($file);

    $content = str_replace('0000-00-00', '1970-01-01', $content);

    file_put_contents($file, $content);
  }
}
