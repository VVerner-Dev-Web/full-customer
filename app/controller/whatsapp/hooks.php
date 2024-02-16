<?php

namespace Full\Customer\WhatsApp;

defined('ABSPATH') || exit;

add_action('admin_menu', 'Full\Customer\WhatsApp\Actions\addMenuPages');
add_action('admin_enqueue_scripts', 'Full\Customer\WhatsApp\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/widget/whatsapp-settings', 'Full\Customer\WhatsApp\Actions\updateSettings');

add_action('wp_footer', 'Full\Customer\WhatsApp\Actions\addButton');
add_action('wp_enqueue_scripts', 'Full\Customer\WhatsApp\Actions\addButtonStyles');
