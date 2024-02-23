<?php

namespace FULL\Customer\Shortcodes;

class Hooks
{
  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_action('admin_menu', [$cls, 'addMenuPage']);
  }

  public function addMenuPage(): void
  {
    add_submenu_page(
      'full-connection',
      'FULL.shortcodes',
      'FULL.shortcodes',
      'edit_posts',
      'full-shortcodes',
      'fullGetAdminPageView'
    );
  }
}

Hooks::attach();
