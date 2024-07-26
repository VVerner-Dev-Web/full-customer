<?php

namespace Full\Customer\Images;

defined('ABSPATH') || exit;

add_filter('full-customer/active-widgets-menu', 'Full\Customer\Images\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\Images\Actions\adminEnqueueScripts');
add_action('wp_ajax_full/widget/image-settings', 'Full\Customer\Images\Actions\updateSettings');

add_action('wp_ajax_full/widget/image-replacement', 'Full\Customer\Images\Actions\replaceImage');
