<?php

namespace Full\Customer\Filters;

use FullCustomer;
use WP_Error;
use WP_REST_Response;
use WP_Post;

defined('ABSPATH') || exit;

function versionsWithUpgrade(array $versions): array
{
  $versions[] = '0.0.9';
  $versions[] = '0.1.1';

  return $versions;
}

function setPluginBranding(array $plugins): array
{
  $key    = plugin_basename(FULL_CUSTOMER_FILE);

  if (!isset($plugins[$key])) :
    return $plugins;
  endif;

  $full = new FullCustomer();

  $plugins[$key]['Name']        = $full->getBranding('plugin-name', $plugins[$key]['Name']);
  $plugins[$key]['Title']       = $full->getBranding('plugin-name', $plugins[$key]['Title']);
  $plugins[$key]['PluginURI']   = $full->getBranding('plugin-url', $plugins[$key]['PluginURI']);
  $plugins[$key]['Description'] = $full->getBranding('plugin-description', $plugins[$key]['Description']);
  $plugins[$key]['Author']      = $full->getBranding('plugin-author', $plugins[$key]['Author']);
  $plugins[$key]['AuthorName']  = $full->getBranding('plugin-author', $plugins[$key]['AuthorName']);
  $plugins[$key]['AuthorURI']   = $full->getBranding('plugin-author-url', $plugins[$key]['AuthorURI']);

  return $plugins;
}

function pluginRowMeta(array $meta, string $plugin): array
{
  if ($plugin !== plugin_basename(FULL_CUSTOMER_FILE)) :
    return $meta;
  endif;

  $full = new FullCustomer();

  if ($full->getBranding('plugin-author', '') === '') :
    return $meta;
  endif;

  foreach ($meta as $key => $action) :
    if (strpos($action, 'open-plugin-details-modal') !== false) :
      unset($meta[$key]);
    endif;
  endforeach;

  $pageUrl =  add_query_arg(['page' => 'full-connection'], admin_url('options-general.php'));

  $meta[] = '<a href="' . $pageUrl .  '">Configurações</a>';
  $meta[] = isSiteConnectedOnFull() ? 'Site conectado!' : '<a href="' . $pageUrl . '">Conectar site</a>';

  return $meta;
}

function wpPhpErrorArgs(array $args, array $error): array
{
  if (strpos($error['file'], dirname(FULL_CUSTOMER_FILE)) === false) :
    return $args;
  endif;

  $error['date'] = current_time('Y-m-d H:i:s');

  update_option('full_customer_last_error', $error, false);

  return $args;
}

function restPreServeRequest(bool $served, WP_REST_Response $response)
{
  if ($served) :
    return $served;
  endif;

  $buffer   = null;

  foreach (array_keys($response->get_headers()) as $header) :
    if ('x-full' !== strtolower($header)) :
      continue;
    endif;

    $buffer   = $response->get_data();
    break;
  endforeach;

  if (!is_string($buffer)) :
    return $served;
  endif;

  echo $buffer;
  return true;
}

function duplicatorRowActions(array $actions, WP_Post $post): array
{
  if (!current_user_can('edit_posts')) :
    return $actions;
  endif;

  $url  = admin_url('admin.php?action=full_duplicator&post=' . $post->ID);
  $url  = wp_nonce_url($url, 'full_duplicator');
  $actions['full_duplicator'] = sprintf('<a href="%s" title="%s">%s</a>', $url, 'FULL.clone', 'FULL.clone');

  return $actions;
}

function fullDuplicatorDuplicate(): void
{
  global $wpdb;

  $nonce   = filter_input(INPUT_GET, '_wpnonce');
  $action  = filter_input(INPUT_GET, 'action');

  $postId  = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);

  if (!$nonce || !$postId || 'full_duplicator' !== $action) :
    return;
  endif;

  $post = sanitize_post(get_post($postId), 'db');

  if (!wp_verify_nonce($nonce, 'full_duplicator') || !$post) :
    return;
  endif;

  $duplicatedId       = wp_insert_post([
    'post_author'    => get_current_user_id(),
    'post_title'     => $post->post_title . ' - Cópia',
    'post_content'   => $post->post_content,
    'post_excerpt'   => $post->post_excerpt,
    'post_parent'    => $post->post_parent,
    'post_status'    => 'draft',
    'ping_status'    => $post->ping_status,
    'comment_status' => $post->comment_status,
    'post_password'  => $post->post_password,
    'post_type'      => $post->post_type,
    'to_ping'        => $post->to_ping,
    'menu_order'     => $post->menu_order,
  ]);

  if (is_wp_error($duplicatedId)) :
    $redirect_url = admin_url('edit.php?post_type=' . $post->post_type . '&full_duplicator_error=' . $duplicatedId->get_error_message());
    wp_safe_redirect($redirect_url);
    exit;
  endif;

  $taxonomies = get_object_taxonomies($post->post_type);
  if (!empty($taxonomies) && is_array($taxonomies)) :
    foreach ($taxonomies as $taxonomy) :
      $post_terms = wp_get_object_terms($postId, $taxonomy, ['fields' => 'slugs']);
      wp_set_object_terms($duplicatedId, $post_terms, $taxonomy, false);
    endforeach;
  endif;

  $sql = "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d";
  $post_meta = $wpdb->get_results($wpdb->prepare($sql, $postId));

  if (!empty($post_meta) && is_array($post_meta)) :
    $exclude_meta_keys = ['_wc_average_rating', '_wc_review_count', '_wc_rating_count'];

    $sql    = "INSERT INTO $wpdb->postmeta ( post_id, meta_key, meta_value ) VALUES ";
    $insert = [];

    foreach ($post_meta as $meta_info) :
      $metaKey   = sanitize_text_field($meta_info->meta_key);
      $metaValue =  $meta_info->meta_value;

      if (in_array($metaKey, $exclude_meta_keys)) :
        continue;
      endif;

      if ('_elementor_template_type' === $metaKey) :
        delete_post_meta($duplicatedId, '_elementor_template_type');
      endif;

      $insert[] = $wpdb->prepare('(%d, %s, %s)', $duplicatedId, $metaKey, $metaValue);
    endforeach;

    $wpdb->query($sql . implode(', ', $insert));
  endif;

  $redirect_url = admin_url('edit.php?post_type=' . $post->post_type . '&full_duplicator_post_id=' . $duplicatedId);
  wp_safe_redirect($redirect_url);
}
