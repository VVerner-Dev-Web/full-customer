<?php

namespace Full\Customer\Ai\Actions;

use Full\Customer\Ai\Settings;

defined('ABSPATH') || exit;

function addMenuPages(): void
{
  add_submenu_page(
    'full-connection',
    'FULL.ai',
    'FULL.ai',
    'edit_posts',
    'full-ai',
    'fullGetAdminPageView'
  );
}

function adminEnqueueScripts(): void
{
  if ('ai' !== fullAdminPageEndpoint()) :
    return;
  endif;

  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  wp_enqueue_script('full-admin-ai', $baseUrl . 'js/admin-ai.js', ['jquery'], $version, true);
}

function copywriterGenerator(): void
{
  check_ajax_referer('full/ai/copywrite-generator');

  $full    = fullCustomer();
  $payload = [
    'site'        => site_url(),
    'subject'     => filter_input(INPUT_POST, 'subject'),
    'seoKeyword'  => filter_input(INPUT_POST, 'seoKeyword'),
    'contentSize' => filter_input(INPUT_POST, 'contentSize'),
    'description' => filter_input(INPUT_POST, 'description'),
  ];

  $url      = $full->getFullDashboardApiUrl() . '-customer/v1/ai/blog-post-generator';
  $request  = wp_remote_post($url, [
    'sslverify' => false,
    'body'      => $payload,
    'timeout'   => MINUTE_IN_SECONDS * 5
  ]);

  $response = wp_remote_retrieve_body($request);
  $response = json_decode($response);

  if (isset($response->error)) :
    wp_send_json_error($response->error);
  endif;

  update_option('full/ai/quota', $response->quota);

  wp_send_json_success([
    'title' => strip_tags(array_shift($response->content)),
    'content' => implode(' ', $response->content),
    'quota'   => $response->quota
  ]);
}

function copywriterPublish(): void
{
  check_ajax_referer('full/ai/copywrite-publish');

  $postId = wp_insert_post([
    'post_title'    => filter_input(INPUT_POST, 'post_title'),
    'post_content'  => filter_input(INPUT_POST, 'post_content'),
    'post_status'   => 'pending'
  ], true);

  if (is_wp_error($postId)) :
    wp_send_json_error($postId->get_error_message());
  endif;

  wp_send_json_success(get_edit_post_link($postId));
}

function listPosts(): void
{
  global $wpdb;

  $types = [];
  $excluded = ['attachment', 'product_variation', 'shop_coupon', 'shop_order'];

  $registered = get_post_types([
    'public'   => true,
  ], 'objects');

  foreach ($registered as $cpt) :
    if (!in_array($cpt->name, $excluded)) :
      $types[$cpt->name] = $cpt->label;
    endif;
  endforeach;

  $sql  = "SELECT ID, post_title, post_type FROM {$wpdb->posts} WHERE 1";
  $sql .= " AND post_type IN ('" . implode("','", array_keys($types)) . "')";
  $sql .= " AND post_status NOT IN ('trash', 'revision', 'inherit')";

  wp_send_json_success([
    'posts' => $wpdb->get_results($sql),
    'types' => $types
  ]);
}

function metadescriptionGenerator(): void
{
  check_ajax_referer('full/ai/metadescription-generator');

  $full    = fullCustomer();
  $payload = [
    'site'    => site_url(),
    'content' => apply_filters('the_content', get_post_field('post_content', filter_input(INPUT_POST, 'postId'))),
  ];

  $url      = $full->getFullDashboardApiUrl() . '-customer/v1/ai/metadescription-generator';
  $request  = wp_remote_post($url, [
    'sslverify' => false,
    'body'      => $payload,
    'timeout'   => MINUTE_IN_SECONDS * 5
  ]);

  $response = wp_remote_retrieve_body($request);
  $response = json_decode($response);

  if (isset($response->error)) :
    wp_send_json_error($response->error);
  endif;

  update_option('full/ai/quota', $response->quota);

  wp_send_json_success([
    'content' => strip_tags(array_shift($response->content)),
    'quota'   => $response->quota
  ]);
}

function metadescriptionPublish(): void
{
  check_ajax_referer('full/ai/metadesc-publish');

  $postId = filter_input(INPUT_POST, 'postId', FILTER_VALIDATE_INT);
  $meta   = filter_input(INPUT_POST, 'metadescription');

  if (!get_post($postId)) :
    wp_send_json_error('Post não localizado para o ID #' . $postId);
  endif;

  wp_update_post([
    'ID' => $postId,
    'post_excerpt' => $meta,
    'meta_input' => [
      '_aioseo_description' => $meta,
      'rank_math_description' => $meta,
      '_yoast_wpseo_metadesc' => $meta,
      '_seopress_titles_desc' => $meta
    ]
  ]);

  wp_send_json_success(get_edit_post_link($postId));
}

function listImagesMissingAlt(): void
{
  global $wpdb;

  $perPage = 21;
  $page = max(filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT), 1) - 1;
  $offset = $perPage * $page;

  $subQuery = "SELECT post_id FROM wp_postmeta WHERE meta_key = '_wp_attachment_image_alt' AND meta_value != ''";
  $sql = "SELECT ID FROM `wp_posts` WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%' AND ID NOT IN ($subQuery)";
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

  if (!$response || isset($response->error)) :
    wp_send_json_error($response?->error ?? 'Não foi possível gerar o conteúdo solicitado');
  endif;

  update_option('full/ai/quota', $response->quota);

  wp_send_json_success([
    'content' => strip_tags(array_shift($response->content)),
    'quota'   => $response->quota
  ]);
}
