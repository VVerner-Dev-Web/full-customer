<?php

namespace Full\Customer\Speed;

defined('ABSPATH') || exit;

class DeprecatedComponents
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('disableDeprecatedComponents')) :
      return;
    endif;

    $cls = new self($env);

    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('template_redirect', 'wp_shortlink_header', 100, 0);
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('embed_head', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    add_filter('style_loader_src', [$cls, 'removeAssetsVersionNumber'], PHP_INT_MAX);
    add_filter('script_loader_src', [$cls, 'removeAssetsVersionNumber'], PHP_INT_MAX);
    add_filter('emoji_svg_url', '__return_false');

    add_action('init', [$cls, 'removeDashicons']);
    add_action('wp_default_scripts', [$cls, 'removeJqueryMigrate']);

    add_filter('tiny_mce_plugins', [$cls, 'disableTinyMceEmojis']);
    add_filter('wp_resource_hints', [$cls, 'disableEmojiDnsFetch'], 10, 2);
  }

  public function disableEmojiDnsFetch(array $urls, $relationType): array
  {
    if ('dns-prefetch' !== $relationType) :
      return $urls;
    endif;

    foreach ($urls as $key => $url) {
      if (false !== strpos($url, 'https://s.w.org/images/core/emoji/')) {
        unset($urls[$key]);
      }
    }

    return $urls;
  }

  public function disableTinyMceEmojis($plugins): array
  {
    return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
  }

  public function removeAssetsVersionNumber(string $src): string
  {
    if (!is_user_logged_in() && strpos($src, '?ver=') !== false) :
      $src = remove_query_arg('ver', $src);
    endif;

    return $src;
  }

  public function removeDashicons(): void
  {
    $currentUrl = sanitize_text_field($_SERVER['REQUEST_URI']);

    if (is_user_logged_in() || false !== strpos($currentUrl, 'wp-')) :
      return;
    endif;

    wp_dequeue_style('dashicons');
    wp_deregister_style('dashicons');
  }

  public function removeEmojis(): void
  {
  }

  public function removeJqueryMigrate($scripts): void
  {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
      $script = $scripts->registered['jquery'];

      if (!empty($script->deps)) {
        $script->deps = array_diff($script->deps, array('jquery-migrate'));
      }
    }
  }
}

DeprecatedComponents::attach();
