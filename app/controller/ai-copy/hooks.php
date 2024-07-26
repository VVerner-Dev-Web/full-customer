<?php

namespace Full\Customer\AiCopy;

defined('ABSPATH') || exit;

add_filter('full-customer/active-widgets-menu', 'Full\Customer\AiCopy\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\AiCopy\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/ai/copywrite-generator', 'Full\Customer\AiCopy\Actions\copywriterGenerator');
add_action('wp_ajax_full/ai/copywrite-publish', 'Full\Customer\AiCopy\Actions\copywriterPublish');
