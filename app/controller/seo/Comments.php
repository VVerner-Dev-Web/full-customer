<?php

namespace Full\Customer\Seo;

defined('ABSPATH') || exit;

class Comments
{
  public Settings $env;

  private function __construct(Settings $env)
  {
    $this->env = $env;
  }

  public static function attach(): void
  {
    $env = new Settings();

    if (!$env->get('disableComments')) :
      return;
    endif;

    $cls = new self($env);
    add_filter('xmlrpc_allow_anonymous_comments', '__return_false');
    add_filter('comments_open', '__return_false');
    add_filter('comments_open', '__return_empty_array');

    add_action('do_meta_boxes', [$cls, 'removeSupports']);
    add_action('template_redirect', [$cls, 'displayBlankComment']);
  }

  public function removeSupports(): void
  {
    foreach (get_post_types() as $cpt) :
      remove_post_type_support($cpt, 'comments');
      remove_post_type_support($cpt, 'trackbacks');
      remove_meta_box('commentstatusdiv', $cpt, 'normal');
      remove_meta_box('commentstatusdiv', $cpt, 'side');
      remove_meta_box('commentsdiv', $cpt, 'normal');
      remove_meta_box('commentsdiv', $cpt, 'side');
      remove_meta_box('trackbacksdiv', $cpt, 'normal');
      remove_meta_box('trackbacksdiv', $cpt, 'side');
    endforeach;

    wp_dequeue_script('admin-comments');
  }

  public function displayBlankComment(): void
  {
    if (is_singular()) {
      add_filter('comments_template', [$this, 'loadBlackTemplate'], 20);
    }
  }

  public function loadBlackTemplate(): string
  {
    return FULL_CUSTOMER_APP . '/views/blank-comment-template.php';
  }
}

Comments::attach();
