<?php

namespace Full\Customer\Api;

use \FullCustomerController;
use \WP_REST_Server;
use \WP_REST_Request;
use \WP_REST_Response;

use Full\Customer\Backup\Controller;

defined('ABSPATH') || exit;

class Backup extends FullCustomerController
{
  private $backup;

  public function __construct()
  {
    $this->backup = new Controller();

    parent::__construct();
  }

  public static function registerRoutes(): void
  {
    $api = new self();

    register_rest_route(self::NAMESPACE, '/backup', [
      [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => [$api, 'createBackup'],
        'permission_callback' => [$api, 'validateToken'],
      ],
      [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => [$api, 'getBackups'],
        'permission_callback' => [$api, 'validateToken'],
      ]
    ]);

    register_rest_route(self::NAMESPACE, '/backup/(?P<backup_id>[0-9\-]+)', [
      [
        'methods'             => WP_REST_Server::EDITABLE,
        'callback'            => [$api, 'restoreBackup'],
        'permission_callback' => [$api, 'validateToken'],
      ],
      [
        'methods'             => WP_REST_Server::DELETABLE,
        'callback'            => [$api, 'deleteBackup'],
        'permission_callback' => [$api, 'validateToken'],
      ]
    ]);
  }

  public function validateToken(): bool
  {
    return true;
  }

  public function createBackup(): WP_REST_Response
  {
    if (function_exists('set_time_limit')) :
      set_time_limit(FULL_BACKUP_TIME_LIMIT);
    endif;

    return new WP_REST_Response([
      'backup_id' => $this->backup->createBackup()
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

    $backupId = 'backup-' . $request->get_param('backup_id');

    return new WP_REST_Response([
      'retored' => $this->backup->restoreBackup($backupId)
    ]);
  }
}
