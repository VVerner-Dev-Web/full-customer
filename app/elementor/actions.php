<?php

namespace Full\Customer\Elementor\Actions;

use Full\Customer\Elementor\TemplateManager;

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
  add_submenu_page(
    'options-general.php',
    'FULL. Templates',
    'FULL. Templates',
    'edit_posts',
    'full-templates',
    'fullGetAdminPageView'
  );
}

function adminEnqueueScripts(): void
{
  $assetsUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/elementor/';
  wp_enqueue_style('full-admin-elementor', $assetsUrl . 'admin.css', [], FULL_CUSTOMER_VERSION);
  wp_enqueue_script('full-admin-elementor', $assetsUrl . 'admin.js', ['jquery'], FULL_CUSTOMER_VERSION, true);
}

function manageElementorLibraryPostsCustomColumn(string $column, int $postId)
{
  if ('full_templates' !== $column) :
    return;
  endif;

  $cloudId = (int) get_post_meta($postId, 'full_cloud_id', true);
  $html    = '<a href="#" data-js="send-to-cloud" data-post="' . $postId . '">Enviar para FULL.</a>';

  if ($cloudId && TemplateManager::instance()->getCloudItem($cloudId)) :
    $html = '<a href="' . fullGetTemplatesUrl('cloud') . '">Gerenciar</a>';
  endif;

  echo  $html;
}
