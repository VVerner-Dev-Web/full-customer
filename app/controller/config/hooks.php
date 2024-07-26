<?php

namespace Full\Customer\Admin;

defined('ABSPATH') || exit;

add_filter('full-customer/active-widgets-menu', 'Full\Customer\Admin\Actions\addMenuPages');

add_action('wp_ajax_full/widget/admin-settings', 'Full\Customer\Admin\Actions\updateSettings');

add_action('admin_enqueue_scripts', 'Full\Customer\Code\Actions\adminEnqueueScripts');
add_action('wp_enqueue_scripts', 'Full\Customer\Code\Actions\enqueueFrontendCustomStyles');
add_action('admin_enqueue_scripts', 'Full\Customer\Code\Actions\enqueueAdminCustomStyles');
add_action('wp_head', 'Full\Customer\Code\Actions\enqueueHeadScripts');
add_action('wp_body_open', 'Full\Customer\Code\Actions\enqueueBodyScripts');
add_action('wp_footer', 'Full\Customer\Code\Actions\enqueueFooterScripts');

add_action('wp_ajax_full/widget/code/update-code', 'Full\Customer\Code\Actions\updateInsertedCode');
add_action('wp_ajax_full/widget/code/update-robots', 'Full\Customer\Code\Actions\updateRobotsFile');
add_action('wp_ajax_full/widget/code/wp-config', 'Full\Customer\Code\Actions\updateWpConfigFile');
