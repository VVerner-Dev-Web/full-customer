<?php

namespace Full\Customer\Elementor\Actions;

defined('ABSPATH') || exit;

function editorBeforeEnqueueStyles(): void
{
  $assetsUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/elementor/';
  wp_enqueue_style('full-elementor', $assetsUrl . 'editor.css', [], FULL_CUSTOMER_VERSION);
}

function editorBeforeEnqueueScripts(): void
{
  $assetsUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/elementor/';
  wp_enqueue_script('full-elementor', $assetsUrl . 'editor.js', ['jquery'], FULL_CUSTOMER_VERSION, true);
}


function addMenuPages(): void
{
  $assetsUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  add_menu_page(
    'FULL. Templates',
    'FULL. Templates',
    'edit_posts',
    'full-templates',
    'fullGetAdminPageView',
    $assetsUrl . 'img/icon.png',
    5
  );
}

function adminEnqueueScripts(): void
{
  $assetsUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/elementor/';
  wp_enqueue_style('full-admin-elementor', $assetsUrl . 'admin.css', [], FULL_CUSTOMER_VERSION);
  wp_enqueue_script('full-admin-elementor', $assetsUrl . 'admin.js', ['jquery'], FULL_CUSTOMER_VERSION, true);
}
