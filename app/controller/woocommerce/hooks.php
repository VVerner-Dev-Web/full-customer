<?php

namespace Full\Customer\WooCommerce;

defined('ABSPATH') || exit;

add_filter('full-customer/active-widgets-menu', 'Full\Customer\WooCommerce\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\WooCommerce\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/widget/woocommerce-settings', 'Full\Customer\WooCommerce\Actions\updateSettings');
