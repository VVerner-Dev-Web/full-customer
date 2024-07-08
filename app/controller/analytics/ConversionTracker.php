<?php

namespace Full\Customer\Analytics;

use DateTimeImmutable;

class ConversionTracker
{
  public int $id;
  public int $conversionId = 0;
  public DateTimeImmutable $createdAt;

  public function __construct()
  {
  }

  public function save(): void
  {
    global $wpdb;

    if (isset($this->id)) :
      return;
    endif;

    if (!isset($this->createdAt)) :
      $this->createdAt = new DateTimeImmutable(current_time('Y-m-d H:i:s'));
    endif;

    $wpdb->insert(
      Database::$conversionTrackerTable,
      [
        'conversionId' => $this->conversionId,
        'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
      ],
      [
        '%d',
        '%s'
      ]
    );
  }
}
