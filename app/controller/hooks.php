<?php

namespace Full\Customer\Hooks;

defined('ABSPATH') || exit;

add_action('rest_api_init', ['\Full\Customer\Api\PluginInstallation', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Connection', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Health', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\ElementorTemplates', 'registerRoutes']);
add_action('rest_api_init', ['\Full\Customer\Api\Whitelabel', 'registerRoutes']);

add_action('plugins_loaded', '\Full\Customer\Actions\initFullElementorTemplates');
