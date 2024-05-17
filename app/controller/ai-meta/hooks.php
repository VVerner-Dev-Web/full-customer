<?php

namespace Full\Customer\Ai;

defined('ABSPATH') || exit;

add_action('admin_menu', 'Full\Customer\AiMeta\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\AiMeta\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/ai/list-posts', 'Full\Customer\AiMeta\Actions\listPosts');
add_action('wp_ajax_full/ai/metadescription-generator', 'Full\Customer\AiMeta\Actions\metadescriptionGenerator');
add_action('wp_ajax_full/ai/metadesc-publish', 'Full\Customer\AiMeta\Actions\metadescriptionPublish');
