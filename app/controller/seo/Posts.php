<?php

namespace Full\Customer\Seo;

use WP_Post;

defined('ABSPATH') || exit;

class Posts
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();
    $cls = new self($env);

    if ($env->get('enableContentDuplication')) :
      add_action('admin_notices', [$cls, 'duplicatorNotice']);
      add_filter('post_row_actions', [$cls, 'duplicatorRowActions'], 0, 2);
      add_filter('page_row_actions', [$cls, 'duplicatorRowActions'], 0, 2);
      add_filter('admin_action_full_duplicator', [$cls, 'fullDuplicatorDuplicate'], 0, 2);
    endif;

    if ($env->get('publishMissingSchedulePosts')) :
      add_action('wp_head', [$cls, 'publishMissingPosts']);
      add_action('admin_head', [$cls, 'publishMissingPosts']);
    endif;
  }

  public function duplicatorNotice(): void
  {
    $file = FULL_CUSTOMER_APP . '/views/admin/notice-duplicator-{status}.php';

    $error  = filter_input(INPUT_GET, 'full_duplicator_error');
    $postId = filter_input(INPUT_GET, 'full_duplicator_post_id', FILTER_VALIDATE_INT);
    $post   = $postId ? get_post($postId) : null;

    $errorFile = str_replace('{status}', 'error', $file);
    $successFile = str_replace('{status}', 'success', $file);

    if ($error && file_exists($errorFile)) :
      require_once $errorFile;
    endif;

    if ($post && file_exists($successFile)) :
      require_once $successFile;
    endif;
  }

  public function duplicatorRowActions(array $actions, WP_Post $post): array
  {
    $full = fullCustomer();

    if (!current_user_can('edit_posts') || !$full->isServiceEnabled('full-clone')) :
      return $actions;
    endif;

    $url  = admin_url('admin.php?action=full_duplicator&post=' . $post->ID);
    $url  = wp_nonce_url($url, 'full_duplicator');
    $actions['full_duplicator'] = sprintf('<a href="%s" title="%s">%s</a>', $url, 'FULL.duplica', 'FULL.duplica');

    return $actions;
  }

  public function fullDuplicatorDuplicate(): void
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

  public function publishMissingPosts(): void
  {
    if (is_front_page() || is_home() || is_page() || is_single() || is_singular() || is_archive() || is_admin() || is_blog_admin() || is_robots() || is_ssl()) {
      $posts = get_transient('full-customer/missing-posts');

      if (false === $posts) {
        global  $wpdb;
        $current_gmt_datetime = gmdate('Y-m-d H:i:00');
        $custom_post_types = get_post_types([
          'public'   => true,
        ], 'names');

        $post_types = "'" . implode("','", $custom_post_types) . "'";

        $sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type IN ({$post_types}) AND post_status='future' AND post_date_gmt<'{$current_gmt_datetime}'";
        $posts = $wpdb->get_col($sql);
        set_transient('full-customer/missing-posts', $posts, 15 * MINUTE_IN_SECONDS);
      }

      if (empty($posts) || !is_array($posts)) {
        return;
      }

      array_map('wp_publish_post', $posts);
    }
  }
}

Posts::attach();
