<?php

namespace Full\Customer\Elementor\Actions;

use Full\Customer\Elementor\TemplateManager;

defined('ABSPATH') || exit;

function editorBeforeEnqueueStyles(): void
{
  $assetsUrl  = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';
  $version    = getFullAssetsVersion();

  wp_enqueue_style('full-swal', $assetsUrl . 'vendor/sweetalert/sweetalert2.min.css', [], '11.4.35');
  wp_enqueue_style('full-icons', 'https://painel.full.services/wp-content/plugins/full/app/assets/vendor/icon-set/style.css');
  wp_enqueue_style('full-admin', $assetsUrl . 'css/admin.css', [], $version);
  wp_enqueue_style('full-admin-elementor', $assetsUrl . 'elementor/admin.css', [], $version);
  wp_enqueue_style('full-elementor', $assetsUrl . 'elementor/editor.css', [], $version);
}

function editorAfterEnqueueScripts(): void
{
  $assetsUrl  = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';
  $version    = getFullAssetsVersion();

  wp_enqueue_script('full-swal', $assetsUrl . 'vendor/sweetalert/sweetalert2.min.js', ['jquery'], '11.4.35', true);
  wp_enqueue_script('full-elementor', $assetsUrl . 'elementor/editor.js', ['jquery'], $version, true);
  wp_enqueue_script('full-admin-elementor', $assetsUrl . 'elementor/admin.js', ['jquery'], $version, true);

  wp_localize_script('full-elementor', 'FULL', fullGetLocalize());
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
  $assetsUrl  = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/elementor/';
  $version    = getFullAssetsVersion();

  wp_enqueue_style('full-admin-elementor', $assetsUrl . 'admin.css', [], $version);
  wp_enqueue_script('full-admin-elementor', $assetsUrl . 'admin.js', ['jquery'], $version, true);
}

function manageElementorLibraryPostsCustomColumn(string $column, int $postId): void
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

function editorFooter(): void
{
  $endpoints = [
    'templates',
    'cloud',
    'single'
  ];

  $templateAsScript = true; // VIEW

  foreach ($endpoints as $endpointView) :
    ob_start();
    require FULL_CUSTOMER_APP . '/views/admin/templates.php';
    $content = ob_get_clean();
    $content = explode('_SCRIPTS_DIVIDER_', $content);

    echo '<script type="text/template" class="full-templates" data-endpoint="' . $endpointView . '">' . array_shift($content) . '</script>';

    foreach ($content as $script) :
      echo $script;
    endforeach;

  endforeach;
}
