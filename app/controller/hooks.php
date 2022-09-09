<?php

namespace Full\Customer\Hooks;

defined('ABSPATH') || exit;

add_action('rest_api_init', ['\FULL_CUSTOMER_Login', 'registerRoutes']);
add_action('rest_api_init', ['\FULL_CUSTOMER_Plugin', 'registerRoutes']);

add_action('wp_footer', '\Full\Customer\Actions\insertFooterNote');

register_activation_hook(FULL_CUSTOMER_FILE, '\Full\Customer\Actions\activationHook');
register_deactivation_hook(FULL_CUSTOMER_FILE, '\Full\Customer\Actions\deactivationHook');

add_filter('wp_is_application_passwords_available', '__return_true', PHP_INT_MAX);
add_filter('wp_is_application_passwords_available_for_user', '__return_true', PHP_INT_MAX);
