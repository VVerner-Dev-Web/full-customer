<?php

namespace Full\Customer\Backup;

class MysqlDump
{
  private $file;

  public function dump(): void
  {
    $this->insertInitialComments();

    $this->insertEmptyLine();
    $this->write('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";');
    $this->write('START TRANSACTION;');
    $this->write('SET time_zone = "+00:00";');
    $this->insertEmptyLine();

    $this->insertComment();
    $this->insertComment('Banco de dados `' . DB_NAME . '`');
    $this->insertComment();

    $this->dumpTables();
  }

  public function openFile(string $filename): void
  {
    $this->file = fopen($filename, 'w+');
  }

  public function closeFile(): void
  {
    fclose($this->file);
  }

  private function dumpTables(): void
  {
    global $wpdb;
    $tables = $wpdb->get_col("SHOW TABLES");

    foreach ($tables as $table) :
      $this->dumpTable($table);
      $this->insertEmptyLine();
    endforeach;
  }

  private function dumpTable(string $table): void
  {
    $this->dumpCreateTable($table);

    $this->insertEmptyLine();

    $this->dumpTableValues($table);
  }

  private function dumpCreateTable(string $table): void
  {
    global $wpdb;

    $create = "SHOW CREATE TABLE $table";
    $create = $wpdb->get_results($create, ARRAY_A);
    $create = $create[0]['Create Table'];

    $create = preg_replace('/\/\*(.+?)\*\//s', '', $create);
    $pattern = array(
      '/\s+CONSTRAINT(.+)REFERENCES(.+),/i',
      '/,\s+CONSTRAINT(.+)REFERENCES(.+)/i',
    );

    $create = preg_replace($pattern, '', $create);

    $pattern = array(
      '/\s+CONSTRAINT(.+)REFERENCES(.+),/i',
      '/,\s+CONSTRAINT(.+)REFERENCES(.+)/i',
    );

    $create = preg_replace($pattern, '', $create);

    $search = array(
      'TYPE=InnoDB',
      'TYPE=MyISAM',
      'ENGINE=Aria',
      'TRANSACTIONAL=0',
      'TRANSACTIONAL=1',
      'PAGE_CHECKSUM=0',
      'PAGE_CHECKSUM=1',
      'TABLE_CHECKSUM=0',
      'TABLE_CHECKSUM=1',
      'ROW_FORMAT=PAGE',
      'ROW_FORMAT=FIXED',
      'ROW_FORMAT=DYNAMIC',
    );
    $replace = array(
      'ENGINE=InnoDB',
      'ENGINE=MyISAM',
      'ENGINE=MyISAM',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
    );

    $create = str_ireplace($search, $replace, $create);

    $this->insertComment();
    $this->insertComment('Estrutura da tabela `' . $table . '`');
    $this->insertComment();

    $this->insertEmptyLine();

    $this->write("DROP TABLE IF EXISTS `{$table}`;");
    $this->write($create . ';');
  }

  private function dumpTableValues(string $table): void
  {
    global $wpdb;

    $this->insertComment();
    $this->insertComment('Extraindo dados da tabela `' . $table . '`');
    $this->insertComment();

    $this->insertEmptyLine();

    $insertInto = "INSERT INTO `$table` ";

    $columns = $wpdb->get_results("SHOW COLUMNS FROM `$table`;");
    $columnsNames = [];
    $nullColumns = [];
    $numericColumns = [];

    foreach ($columns as $column) :
      $columnsNames[] = $column->Field;

      if (strtoupper($column->Null) === 'YES') :
        $nullColumns[] = $column->Field;
      endif;

      if (strpos($column->Type, 'int') !== false) :
        $numericColumns[] = $column->Field;
      endif;

    endforeach;

    $rows = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
    $values = [];

    foreach ($rows as $row) :
      $values[] = $this->formatRowValuesSqlQuery($row, $nullColumns, $numericColumns);
    endforeach;

    if ($values) :
      $insertInto .= '(`' . implode('`, `', $columnsNames) . '`) VALUES ';
      $values = implode(', ' . PHP_EOL, $values);
      $values .= ';';

      $this->write($insertInto);
      $this->write($values);
    endif;
  }

  private function formatRowValuesSqlQuery(array $row, array $nullColumns, array $numericColumns): string
  {
    global $wpdb;
    $value    = [];

    foreach ($row as $col => $colValue) :
      $colValue = mysqli_real_escape_string($wpdb->dbh, $colValue);

      if (in_array($col, $nullColumns) && $colValue === '' || $colValue === null) :
        $value[] = "NULL";
        continue;

      elseif (in_array($col, $numericColumns)) :
        $value[] = $colValue;
        continue;

      endif;

      $value[] = "'$colValue'";

    endforeach;

    return '(' . implode(', ', $value) . ')';
  }

  private function insertInitialComments(): void
  {
    $this->insertComment('FULL SQL Dump');
    $this->insertComment('Version 1.0.0');
    $this->insertEmptyLine();
    $this->insertComment('Host: ' . DB_HOST);
    $this->insertComment('Data e hora do backup: ' . current_time('d/m/Y \à\s H:i:s'));
  }

  private function insertComment(string $text = ''): void
  {
    $this->write('-- ' . $text);
  }

  private function insertEmptyLine(): void
  {
    $this->write('');
  }

  private function write(string $text): void
  {
    fwrite($this->file, $text . PHP_EOL);
  }
}
