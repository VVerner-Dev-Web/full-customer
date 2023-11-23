<?php

namespace Full\Security\Code;

defined('ABSPATH') || exit;

add_action('admin_menu', 'Full\Customer\Security\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\Security\Actions\adminEnqueueScripts');
add_action('wp_ajax_full/widget/security-settings', 'Full\Customer\Security\Actions\updateSettings');
