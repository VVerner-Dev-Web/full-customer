<?php

namespace Full\Customer\Email;

defined('ABSPATH') || exit;

add_filter('full-customer/active-widgets-menu', 'Full\Customer\Email\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\Email\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/widget/email-settings', 'Full\Customer\Email\Actions\updateEmailSettings');
add_action('wp_ajax_full/widget/email-test', 'Full\Customer\Email\Actions\sendTestEmail');
