<?php

namespace Full\Customer\Hooks;

use Full\Customer\Backup\Cron;

defined('ABSPATH') || exit;

register_activation_hook(FULL_CUSTOMER_FILE, '\Full\Customer\Actions\verifySiteConnection');
register_activation_hook(FULL_CUSTOMER_FILE, '\Full\Customer\Actions\activationAnalyticsHook');
register_deactivation_hook(FULL_CUSTOMER_FILE, '\Full\Customer\Actions\deactivationAnalyticsHook');

add_action('rest_api_init', ['\Full\Customer\Api\Login', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Plugin', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Connection', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Whitelabel', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Backup', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Health', 'registerRoutes']);

add_action('wp_footer', '\Full\Customer\Actions\insertFooterNote');
add_action('admin_menu', '\Full\Customer\Actions\addMenuPage');
add_action('admin_enqueue_scripts', '\Full\Customer\Actions\adminEnqueueScripts');
add_action('plugins_loaded', '\Full\Customer\Actions\upgradePlugin');
add_action('admin_notices', '\Full\Customer\Actions\insertAdminNotice');

add_action('wp', ['\Full\Customer\Backup\Cron', 'enqueueCreateHook']);
add_action(Cron::JOB_NAME, '\Full\Customer\Actions\createCronBackup');
add_action(Cron::ASYNC_JOB_NAME, '\Full\Customer\Actions\createAsyncCronBackup');
add_action(Cron::ASYNC_RESTORE_JOB_NAME, '\Full\Customer\Actions\restoreAsyncBackup');

add_filter('wp_is_application_passwords_available', '__return_true', PHP_INT_MAX);
add_filter('wp_is_application_passwords_available_for_user', '__return_true', PHP_INT_MAX);

add_filter('full-versions-upgrades', '\Full\Customer\Filters\versionsWithUpgrade');
add_filter('all_plugins', '\Full\Customer\Filters\setPluginBranding');
add_filter('plugin_row_meta', '\Full\Customer\Filters\pluginRowMeta', 10, 2);
