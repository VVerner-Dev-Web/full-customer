<?php

namespace Full\Customer\Hooks;

use Full\Customer\Backup\Cron as BackupCron;
use Full\Customer\Proxy;

defined('ABSPATH') || exit;

register_activation_hook(FULL_CUSTOMER_FILE, '\Full\Customer\Actions\verifySiteConnection');
register_activation_hook(FULL_CUSTOMER_FILE, '\Full\Customer\Actions\activationAnalyticsHook');
register_deactivation_hook(FULL_CUSTOMER_FILE, '\Full\Customer\Actions\deactivationAnalyticsHook');

add_action('rest_api_init', ['\Full\Customer\Api\Login', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\PluginInstallation', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\PluginUpdate', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Connection', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Whitelabel', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Backup', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Health', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\ElementorTemplates', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\ElementorAi', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Widgets', 'registerRoutes']);

add_action('wp_footer', '\Full\Customer\Actions\insertFooterNote');
add_action('admin_menu', '\Full\Customer\Actions\addMenuPage');
add_action('admin_enqueue_scripts', '\Full\Customer\Actions\adminEnqueueScripts');
add_action('plugins_loaded', '\Full\Customer\Actions\upgradePlugin');
add_action('plugins_loaded', '\Full\Customer\Actions\initFullElementorTemplates');
add_action('plugins_loaded', '\Full\Customer\Actions\initFullLoginWidget');
add_action('plugins_loaded', '\Full\Customer\Actions\initFullEmailWidget');
add_action('plugins_loaded', '\Full\Customer\Actions\initFullImagesWidget');
add_action('plugins_loaded', '\Full\Customer\Actions\initFullCodeWidget');
add_action('plugins_loaded', '\Full\Customer\Actions\initFullSpeedWidget');
add_action('admin_notices', '\Full\Customer\Actions\insertAdminNotice');
add_action('admin_notices', '\Full\Customer\Actions\duplicatorNotice');
add_action('shutdown', '\Full\Customer\Actions\notifyPluginError');

add_action('wp', ['\Full\Customer\Backup\Cron', 'enqueueCreateHook']);
add_action(BackupCron::JOB_NAME, '\Full\Customer\Actions\createCronBackup');
add_action(BackupCron::ASYNC_JOB_NAME, '\Full\Customer\Actions\createAsyncCronBackup');
add_action(BackupCron::ASYNC_RESTORE_JOB_NAME, '\Full\Customer\Actions\restoreAsyncBackup', 10, 3);

add_action('wp', ['\Full\Customer\Proxy', 'enqueueCreateHook']);
add_action(Proxy::CRON_JOB_NAME, ['\Full\Customer\Proxy', 'cronJob']);

add_action('plugins_loaded', ['\Full\Customer\Firewall', 'run'], 0);

add_filter('wp_is_application_passwords_available', '__return_true', PHP_INT_MAX);
add_filter('wp_is_application_passwords_available_for_user', '__return_true', PHP_INT_MAX);

add_filter('full-versions-upgrades', '\Full\Customer\Filters\versionsWithUpgrade');
add_filter('all_plugins', '\Full\Customer\Filters\setPluginBranding');
add_filter('plugin_row_meta', '\Full\Customer\Filters\pluginRowMeta', 10, 2);
add_filter('wp_php_error_args', '\Full\Customer\Filters\wpPhpErrorArgs', PHP_INT_MAX, 2);
add_filter('rest_pre_serve_request', '\Full\Customer\Filters\restPreServeRequest', 0, 2);

add_filter('post_row_actions', '\Full\Customer\Filters\duplicatorRowActions', 0, 2);
add_filter('page_row_actions', '\Full\Customer\Filters\duplicatorRowActions', 0, 2);
add_filter('admin_action_full_duplicator', '\Full\Customer\Filters\fullDuplicatorDuplicate', 0, 2);
