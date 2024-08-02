<?php

namespace Full\Customer\Seo;

defined('ABSPATH') || exit;

add_action('wp_ajax_full/widget/content-settings', 'Full\Customer\Seo\Actions\updateSettings');

add_action('admin_enqueue_scripts', 'Full\Customer\Seo\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/ai/list-images-missing-alt', 'Full\Customer\Seo\Actions\listImagesMissingAlt');
add_action('wp_ajax_full/ai/update-image-alt', 'Full\Customer\Seo\Actions\imageAltUpdate');
add_action('wp_ajax_full/ai/generate-image-alt', 'Full\Customer\Seo\Actions\imageAltGenerator');

add_action('admin_enqueue_scripts', 'Full\Customer\Seo\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/ai/list-posts', 'Full\Customer\Seo\Actions\listPosts');
add_action('wp_ajax_full/ai/metadescription-generator', 'Full\Customer\Seo\Actions\metadescriptionGenerator');
add_action('wp_ajax_full/ai/metadesc-publish', 'Full\Customer\Seo\Actions\metadescriptionPublish');
