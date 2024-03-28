<?php

namespace Full\Customer\Speed;

defined('ABSPATH') || exit;

class BlockBasedFeatures
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

    if ($env->get('disableGutenberg')) :
      add_action('admin_init', [$cls, 'disableGutenberg']);
      add_action('wp_enqueue_scripts', [$cls, 'disableGutenbergStyles'], 100);
    endif;

    if ($env->get('disableBlockWidgets')) :
      add_filter('gutenberg_use_widgets_block_editor', '__return_false');
      add_filter('use_widgets_block_editor', '__return_false');
    endif;
  }

  public function disableGutenberg(): void
  {
    $gutenberg = function_exists('gutenberg_register_scripts_and_styles');

    $block_editor = has_action('enqueue_block_assets');

    if (!$gutenberg && (false === $block_editor)) :
      return;
    endif;

    add_filter('use_block_editor_for_post_type', '__return_false', 100);

    if ($gutenberg) {
      add_filter('gutenberg_can_edit_post_type', '__return_false', 100);

      remove_action('admin_menu', 'gutenberg_menu');
      remove_action('admin_init', 'gutenberg_redirect_demo');

      remove_action('wp_enqueue_scripts', 'gutenberg_register_scripts_and_styles');
      remove_action('admin_enqueue_scripts', 'gutenberg_register_scripts_and_styles');
      remove_action('admin_notices', 'gutenberg_wordpress_version_notice');
      remove_action('rest_api_init', 'gutenberg_register_rest_widget_updater_routes');
      remove_action('admin_print_styles', 'gutenberg_block_editor_admin_print_styles');
      remove_action('admin_print_scripts', 'gutenberg_block_editor_admin_print_scripts');
      remove_action('admin_print_footer_scripts', 'gutenberg_block_editor_admin_print_footer_scripts');
      remove_action('admin_footer', 'gutenberg_block_editor_admin_footer');
      remove_action('admin_enqueue_scripts', 'gutenberg_widgets_init');
      remove_action('admin_notices', 'gutenberg_build_files_notice');

      remove_filter('load_script_translation_file', 'gutenberg_override_translation_file');
      remove_filter('block_editor_settings', 'gutenberg_extend_block_editor_styles');
      remove_filter('default_content', 'gutenberg_default_demo_content');
      remove_filter('default_title', 'gutenberg_default_demo_title');
      remove_filter('block_editor_settings', 'gutenberg_legacy_widget_settings');
      remove_filter('rest_request_after_callbacks', 'gutenberg_filter_oembed_result');

      remove_filter('wp_refresh_nonces', 'gutenberg_add_rest_nonce_to_heartbeat_response_headers');
      remove_filter('get_edit_post_link', 'gutenberg_revisions_link_to_editor');
      remove_filter('wp_prepare_revision_for_js', 'gutenberg_revisions_restore');

      remove_action('rest_api_init', 'gutenberg_register_rest_routes');
      remove_action('rest_api_init', 'gutenberg_add_taxonomy_visibility_field');
      remove_filter('registered_post_type', 'gutenberg_register_post_prepare_functions');

      remove_action('do_meta_boxes', 'gutenberg_meta_box_save');
      remove_action('submitpost_box', 'gutenberg_intercept_meta_box_render');
      remove_action('submitpage_box', 'gutenberg_intercept_meta_box_render');
      remove_action('edit_page_form', 'gutenberg_intercept_meta_box_render');
      remove_action('edit_form_advanced', 'gutenberg_intercept_meta_box_render');
      remove_filter('redirect_post_location', 'gutenberg_meta_box_save_redirect');
      remove_filter('filter_gutenberg_meta_boxes', 'gutenberg_filter_meta_boxes');

      remove_filter('body_class', 'gutenberg_add_responsive_body_class');
      remove_filter('admin_url', 'gutenberg_modify_add_new_button_url'); // old
      remove_action('admin_enqueue_scripts', 'gutenberg_check_if_classic_needs_warning_about_blocks');
      remove_filter('register_post_type_args', 'gutenberg_filter_post_type_labels');
    }
  }

  public function disableGutenbergStyles(): void
  {
    global $wp_styles;

    foreach ($wp_styles->queue as $handle) :
      if (false !== strpos($handle, 'wp-block')) {
        wp_dequeue_style($handle);
      }
    endforeach;

    wp_dequeue_style('core-block-supports');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
  }
}

BlockBasedFeatures::attach();
