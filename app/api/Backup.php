<?php

namespace Full\Customer\Api;

use \FullCustomerController;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

use Full\Customer\Backup\Controller;
use Full\Customer\Backup\Cron;

defined('ABSPATH') || exit;

class Backup extends FullCustomerController
{
  private $backup;
  private $cron;

  public function __construct()
  {
    $this->backup = new Controller();
    $this->cron   = new Cron();

    parent::__construct();
  }

  public static function registerRoutes(): void
  {
    $api = new self();
    $permissionCallback = $api->env->getCurrentEnv() === 'DEV' ? '__return_true' : 'is_user_logged_in';

    register_rest_route(self::NAMESPACE, '/backup', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'createBackup'],
        'permission_callback' => $permissionCallback,
      ],
      [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => [$api, 'getBackups'],
        'permission_callback' => $permissionCallback,
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/backup/cron', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'setCronSettings'],
        'permission_callback' => $permissionCallback,
      ],
      [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => [$api, 'getCronSettings'],
        'permission_callback' => $permissionCallback,
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/backup/(?P<backup_id>[0-9\-]+)', [
      [
        'methods'             => WP_REST_Server::EDITABLE,
        'callback'            => [$api, 'restoreBackup'],
        'permission_callback' => $permissionCallback,
      ],
      [
        'methods'             => WP_REST_Server::DELETABLE,
        'callback'            => [$api, 'deleteBackup'],
        'permission_callback' => $permissionCallback,
      ]
    ]);
  }

  public function setCronSettings(WP_REST_Request $request): WP_REST_Response
  {
    $interval = $this->cron->setCronInterval($request->get_param('interval'));
    $quantity = $this->cron->setBackupsQuantityToMaintain((int) $request->get_param('quantity'));

    return new WP_REST_Response([
      'updated'       => $interval && $quantity,
      'schedule_date' => $this->cron->getNextScheduleDate() ? $this->cron->getNextScheduleDate()->format('Y-m-d H:i:s') : null
    ]);
  }

  public function getCronSettings(): WP_REST_Response
  {
    return new WP_REST_Response([
      'schedule_date' => $this->cron->getNextScheduleDate() ? $this->cron->getNextScheduleDate()->format('Y-m-d H:i:s') : null,
      'interval'      => $this->cron->getCronInterval(),
      'quantity'      => $this->cron->getBackupsQuantityToMaintain()
    ]);
  }

  public function createBackup(WP_REST_Request $request): WP_REST_Response
  {
    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    $asyncRequest = $request->get_param('async') ? true : false;

    return new WP_REST_Response([
      'backup_id' => $asyncRequest ? $this->backup->createAsyncBackup() : $this->backup->createBackup()
    ]);
  }

  public function getBackups(): WP_REST_Response
  {
    return new WP_REST_Response([
      'backups' => $this->backup->getBackups()
    ]);
  }

  public function deleteBackup(WP_REST_Request $request): WP_REST_Response
  {
    $backupId = 'backup-' . $request->get_param('backup_id');

    return new WP_REST_Response([
      'deleted' => $this->backup->deleteBackup($backupId)
    ]);
  }

  public function restoreBackup(WP_REST_Request $request): WP_REST_Response
  {
    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    $backupId     = 'backup-' . $request->get_param('backup_id');
    $asyncRequest = $request->get_param('async') ? true : false;

    return new WP_REST_Response([
      'restored' => $asyncRequest ? $this->backup->restoreAsyncBackup($backupId) : $this->backup->restoreBackup($backupId)
    ]);
  }
}
