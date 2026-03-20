<?php

namespace Full\Customer\Hooks;

use Full\Customer\License;

defined('ABSPATH') || exit;

add_action('rest_api_init', ['\Full\Customer\Api\PluginInstallation', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Connection', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Health', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\ElementorTemplates', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Whitelabel', 'registerRoutes']);

add_action('wp_footer', '\Full\Customer\Actions\insertFooterNote');
add_action('shutdown', '\Full\Customer\Actions\notifyPluginError');

add_action('plugins_loaded', '\Full\Customer\Actions\initFullElementorTemplates');

add_filter('all_plugins', '\Full\Customer\Filters\setPluginBranding');
add_filter('plugin_row_meta', '\Full\Customer\Filters\pluginRowMeta', 10, 2);
add_filter('wp_php_error_args', '\Full\Customer\Filters\notifyPluginError', PHP_INT_MAX, 2);
