<?php

namespace Full\Customer\WhatsApp;

defined('ABSPATH') || exit;

add_filter('full-customer/active-widgets-menu', 'Full\Customer\WhatsApp\Actions\addMenuPages');
add_action('add_meta_boxes', 'Full\Customer\WhatsApp\Actions\addMetaBoxes');
add_action('save_post', 'Full\Customer\WhatsApp\Actions\maybeUpdateSinglePostSettings');
add_action('admin_enqueue_scripts', 'Full\Customer\WhatsApp\Actions\adminEnqueueScripts');

add_action('wp_ajax_full/widget/whatsapp-settings', 'Full\Customer\WhatsApp\Actions\updateSettings');

add_action('wp_footer', 'Full\Customer\WhatsApp\Actions\addButton');
add_action('wp_enqueue_scripts', 'Full\Customer\WhatsApp\Actions\addButtonStyles');
