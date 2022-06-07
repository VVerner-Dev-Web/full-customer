<?php 

namespace Full\Customer\Hooks;

defined('ABSPATH') || exit;

add_action('rest_api_init', ['\FULL_CUSTOMER_Login', 'registerRoutes']);
add_action('wp_footer', '\Full\Customer\Actions\insertFooterNote');
