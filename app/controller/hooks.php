<?php

namespace Full\Customer\Hooks;

defined('ABSPATH') || exit;

register_activation_hook(FULL_CUSTOMER_FILE, '\Full\Customer\Actions\activationHook');
register_deactivation_hook(FULL_CUSTOMER_FILE, '\Full\Customer\Actions\deactivationHook');

add_action('rest_api_init', ['\FULL_CUSTOMER_Login', 'registerRoutes']);
add_action('rest_api_init', ['\FULL_CUSTOMER_Plugin', 'registerRoutes']);
add_action('rest_api_init', ['\FULL_CUSTOMER_Connection', 'registerRoutes']);

add_action('wp_footer', '\Full\Customer\Actions\insertFooterNote');
add_action('admin_menu', '\Full\Customer\Actions\addMenuPage');
add_action('admin_enqueue_scripts', '\Full\Customer\Actions\adminEnqueueScripts');
add_action('plugins_loaded', '\Full\Customer\Actions\upgradePlugin');

add_filter('wp_is_application_passwords_available', '__return_true', PHP_INT_MAX);
add_filter('wp_is_application_passwords_available_for_user', '__return_true', PHP_INT_MAX);

add_filter('full-versions-upgrades', '\Full\Customer\Filters\versionsWithUpgrade');
