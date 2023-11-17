<?php

namespace Full\Customer\Admin;

defined('ABSPATH') || exit;

add_action('admin_menu', 'Full\Customer\Admin\Actions\addMenuPages');

add_action('wp_ajax_full/widget/admin-settings', 'Full\Customer\Admin\Actions\updateSettings');
