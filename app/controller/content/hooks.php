<?php

namespace Full\Customer\Content;

defined('ABSPATH') || exit;

add_action('admin_menu', 'Full\Customer\Content\Actions\addMenuPages');
add_action('wp_ajax_full/widget/content-settings', 'Full\Customer\Content\Actions\updateSettings');
