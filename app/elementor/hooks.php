<?php

namespace Full\Customer\Elementor;

defined('ABSPATH') || exit;

add_action('admin_menu', 'Full\Customer\Elementor\Actions\addMenuPages');
add_action('admin_enqueue_scripts', '\Full\Customer\Elementor\Actions\adminEnqueueScripts');

add_action('elementor/editor/before_enqueue_styles', 'Full\Customer\Elementor\Actions\editorBeforeEnqueueStyles');
add_action('elementor/editor/before_enqueue_scripts', 'Full\Customer\Elementor\Actions\editorBeforeEnqueueScripts');

add_filter('manage_elementor_library_posts_columns', 'Full\Customer\Elementor\Filters\manageElementorLibraryPostsColumns');
add_action('manage_elementor_library_posts_custom_column', 'Full\Customer\Elementor\Actions\manageElementorLibraryPostsCustomColumn', 10, 2);
