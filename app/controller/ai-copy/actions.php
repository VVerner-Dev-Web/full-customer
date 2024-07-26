<?php

namespace Full\Customer\AiCopy\Actions;

defined('ABSPATH') || exit;

function addMenuPages(array $menu): array
{
  $menu[] = [
    'name' => 'FULL.copy',
    'endpoint' => 'full-ai-copy'
  ];

  return $menu;
}

function adminEnqueueScripts(): void
{
  if ('ai-copy' !== fullAdminPageEndpoint()) :
    return;
  endif;

  $version = getFullAssetsVersion();
  $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

  wp_enqueue_style('full-select2', $baseUrl . 'vendor/select2/select2.min.css', [], '4.1.0');
  wp_enqueue_script('full-select2', $baseUrl . 'vendor/select2/select2.min.js', ['jquery'], '4.1.0', true);

  wp_enqueue_script('full-admin-ai-copy', $baseUrl . 'js/admin-ai-copy.js', ['jquery'], $version, true);
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
