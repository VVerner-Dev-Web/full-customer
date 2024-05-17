<?php

namespace Full\Customer\AiImages\Actions;

defined('ABSPATH') || exit;

function addMenuPages(): void
{
  add_submenu_page(
    'full-connection',
    'FULL.ai - Images',
    'FULL.ai - Images',
    'edit_posts',
    'full-ai-images',
    'fullGetAdminPageView'
  );
}

function adminEnqueueScripts(): void
{
  if ('ai-images' !== fullAdminPageEndpoint()) :
    return;
  endif;

  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  wp_enqueue_style('full-select2', $baseUrl . 'vendor/select2/select2.min.css', [], '4.1.0');
  wp_enqueue_script('full-select2', $baseUrl . 'vendor/select2/select2.min.js', ['jquery'], '4.1.0', true);

  wp_enqueue_script('full-admin-ai-images', $baseUrl . 'js/admin-ai-images.js', ['jquery'], $version, true);
}

function listImagesMissingAlt(): void
{
  global $wpdb;

  $perPage = 21;
  $page = max(filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT), 1) - 1;
  $offset = $perPage * $page;

  $subQuery = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attachment_image_alt' AND meta_value != ''";
  $sql  = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%' AND ID NOT IN ($subQuery)";
  $sql .= " ORDER BY ID DESC";

  $total = count($wpdb->get_col($sql));

  $sql .= " LIMIT $offset,$perPage";

  $ids = $wpdb->get_col($sql);

  wp_send_json_success([
    'currentPage' => $page + 1,
    'totalPages'  => ceil($total / $perPage),
    'totalItems'  => $total,
    'loadedItems' => $perPage * $page + count($ids),
    'items'       => array_combine(
      $ids,
      array_map('wp_get_attachment_url', $ids)
    )
  ]);
}

function imageAltUpdate(): void
{
  $attachmentId = filter_input(INPUT_POST, 'attachmentId', FILTER_VALIDATE_INT);
  $content = sanitize_textarea_field(filter_input(INPUT_POST, 'generatedContent'));

  if (!wp_get_attachment_url($attachmentId)) :
    wp_send_json_error('Imagem não localizada para o ID #' . $attachmentId);
  endif;

  update_post_meta($attachmentId, '_wp_attachment_image_alt', $content);
  wp_send_json_success();
}

function imageAltGenerator(): void
{
  $full    = fullCustomer();
  $payload = [
    'site'     => site_url(),
    'imageUrl' => wp_get_attachment_url(filter_input(INPUT_POST, 'attachmentId', FILTER_VALIDATE_INT)),
  ];

  $url      = $full->getFullDashboardApiUrl() . '-customer/v1/ai/image-alt-generator';
  $request  = wp_remote_post($url, [
    'sslverify' => false,
    'body'      => $payload,
    'timeout'   => MINUTE_IN_SECONDS * 5
  ]);

  $response = wp_remote_retrieve_body($request);
  $response = json_decode($response);

  if (!$response || !isset($response->content)) :
    wp_send_json_error('Não foi possível gerar o conteúdo solicitado');
  elseif (isset($response->error)) :
    wp_send_json_error($response->error);
  endif;

  update_option('full/ai/quota', $response->quota);

  wp_send_json_success([
    'content' => strip_tags(array_shift($response->content)),
    'quota'   => $response->quota
  ]);
}
