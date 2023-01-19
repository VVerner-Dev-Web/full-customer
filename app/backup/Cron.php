<?php

namespace Full\Customer\Backup;

use DateTime;

class Cron
{
  public const JOB_NAME       = 'full-customer/backup';
  public const ASYNC_JOB_NAME = 'full-customer/async-backup';
  public const ASYNC_RESTORE_JOB_NAME = 'full-customer/async-restore';

  private const INTERVAL_OPTION  = 'full-customer/backup/interval';
  private const QUANTITY_OPTION  = 'full-customer/backup/maintain-quantity';
  private const DISABLED_CRON    = 'off';

  public static function enqueueCreateHook(): void
  {
    $cron = new self();

    if (!$cron->getNextScheduleDate() && $cron->getCronInterval() !== self::DISABLED_CRON) :
      wp_schedule_event(strtotime('01:00:00'), $cron->getCronInterval(), self::JOB_NAME);
    endif;
  }

  public function enqueueAsyncCreateHook(): void
  {
    wp_schedule_single_event(time() + MINUTE_IN_SECONDS, self::ASYNC_JOB_NAME);
  }

  public function enqueueAsyncRestoreHook(string $backupId): void
  {
    wp_schedule_single_event(time() + MINUTE_IN_SECONDS, self::ASYNC_RESTORE_JOB_NAME, [$backupId]);
  }

  public function getNextScheduleDate(): ?DateTime
  {
    $time = wp_next_scheduled(self::JOB_NAME);
    return $time ? new DateTime(date('Y-m-d H:i:s', $time)) : null;
  }

  public function setCronInterval(string $interval): bool
  {
    $options = wp_get_schedules();

    if (!isset($options[$interval]) && $interval !== 'off') :
      return false;
    endif;

    update_option(self::INTERVAL_OPTION, $interval, false);

    $interval === self::DISABLED_CRON ?
      wp_clear_scheduled_hook(self::JOB_NAME) :
      $this->enqueueCreateHook();

    return true;
  }

  public function getCronInterval(): string
  {
    return get_option(self::INTERVAL_OPTION, self::DISABLED_CRON);
  }

  public function setBackupsQuantityToMaintain(int $quantity): bool
  {
    update_option(self::QUANTITY_OPTION, $quantity, false);
    return true;
  }

  public function getBackupsQuantityToMaintain(): string
  {
    return get_option(self::QUANTITY_OPTION, 0);
  }
}
