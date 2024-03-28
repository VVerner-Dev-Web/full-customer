<?php

namespace Full\Customer\Security;

defined('ABSPATH') || exit;

class Feeds
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

    if ($env->get('disableFeeds')) :
      remove_action('wp_head', 'feed_links', 2);
      remove_action('wp_head', 'feed_links_extra', 3);

      add_action('do_feed_rdf', [$cls, 'redirectFeed'], 1);
      add_action('do_feed_rss', [$cls, 'redirectFeed'], 1);
      add_action('do_feed_rss2', [$cls, 'redirectFeed'], 1);
      add_action('do_feed_atom', [$cls, 'redirectFeed'], 1);
      add_action('do_feed_rss2_comments', [$cls, 'redirectFeed'], 1);
      add_action('do_feed_atom_comments', [$cls, 'redirectFeed'], 1);
    endif;

    if ($env->get('disableXmlrpc')) :
      add_filter('xmlrpc_enabled', '__return_false', PHP_INT_MAX);
      add_filter('wp_xmlrpc_server_class', [$cls, 'redirectXmlrpc']);
    endif;
  }

  public function redirectFeed(): void
  {
    wp_safe_redirect(home_url());
    exit;
  }

  public function redirectXmlrpc(): void
  {
    http_response_code(403);
    exit('You don\'t have permission to access this file.');
  }
}

Feeds::attach();
