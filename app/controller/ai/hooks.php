<?php

namespace Full\Customer\Ai;

defined('ABSPATH') || exit;

add_action('admin_menu', 'Full\Customer\Ai\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\Ai\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/ai/copywrite-generator', 'Full\Customer\Ai\Actions\copywriterGenerator');
add_action('wp_ajax_full/ai/copywrite-publish', 'Full\Customer\Ai\Actions\copywriterPublish');
