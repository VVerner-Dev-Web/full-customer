<?php

namespace Full\Customer\Security;

defined('ABSPATH') || exit;

add_filter('full-customer/active-widgets-menu', 'Full\Customer\Security\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\Security\Actions\adminEnqueueScripts');
add_action('wp_ajax_full/widget/security-settings', 'Full\Customer\Security\Actions\updateSettings');
