<?php

namespace Full\Customer\Admin;

defined('ABSPATH') || exit;

class AdminInterface
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

    if ($env->get('clearTopBar')) :
      add_filter('admin_bar_menu', [$cls, 'removeTopBarElements'], 5);
      add_action('admin_head', [$cls, 'removeHelps']);
    endif;

    if ($env->get('disableDashboardWidgets')) :
      add_action('wp_dashboard_setup', [$cls, 'disableDashboardWidgets'], 99);
    endif;

    if ($env->get('hideAdminBarOnFrontend')) :
      add_filter('show_admin_bar', '__return_false');
    endif;

    if (160 != $env->get('sidebarWidth')) :
      add_action('admin_head', [$cls, 'sidebarWidth'], 99);
    endif;
  }

  public function sidebarWidth(): void
  {
    $margin = is_rtl() ? 'margin-right' : 'margin-left';
    $position = is_rtl() ? 'right' : 'left';
    $width = $this->env->get('sidebarWidth') . 'px';

    echo "<style>
      #wpcontent, #wpfooter {
        $margin: $width;
      }
      
      #adminmenuback, #adminmenuwrap, #adminmenu, #adminmenu .wp-submenu {
        width: $width;
      }
      
      #adminmenu .wp-submenu {
        $position: $width;
      }
      
      #adminmenu .wp-not-current-submenu .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu {
        min-width: $width;
      }
      
      .woocommerce-layout__header {
        width: calc(100% - $width);
      }
    </style>";
  }

  public function disableDashboardWidgets(): void
  {
    global $wp_meta_boxes;
    $wp_meta_boxes['dashboard'] = [];
  }

  public function removeTopBarElements(): void
  {
    remove_action('admin_bar_menu', 'wp_admin_bar_wp_menu', 10);
    remove_action('admin_bar_menu', 'wp_admin_bar_customize_menu', 40);
    remove_action('admin_bar_menu', 'wp_admin_bar_updates_menu', 50);
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    remove_action('admin_bar_menu', 'wp_admin_bar_new_content_menu', 70);
  }

  public function removeHelps(): void
  {
    if (is_admin()) :
      get_current_screen()->remove_help_tabs();
    endif;
  }
}

AdminInterface::attach();
