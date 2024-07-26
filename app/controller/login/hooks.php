<?php

namespace Full\Customer\Login;

defined('ABSPATH') || exit;

add_filter('full-customer/active-widgets-menu', 'Full\Customer\Login\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\Login\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/widget/login-settings', 'Full\Customer\Login\Actions\updateLoginSettings');
