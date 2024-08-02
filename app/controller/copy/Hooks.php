<?php

namespace Full\Customer\Copy;

defined('ABSPATH') || exit;

class Hooks
{
  private function __construct()
  {
  }

  public static function attach(): void
  {
    $cls = new self();

    add_filter('full-customer/active-widgets-menu', [$cls, 'addMenuPages']);

    add_action('admin_enqueue_scripts', [$cls, 'assets']);

    add_action('wp_ajax_full/copy/generate-from-text', [$cls, 'generateFromText']);
  }

  public function addMenuPages(array $menu): array
  {
    $menu[] = [
      'name' => 'FULL.copy',
      'endpoint' => 'full-copy'
    ];

    return $menu;
  }

  public function assets(): void
  {
    if ('copy' !== fullAdminPageEndpoint()) :
      return;
    endif;

    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    wp_enqueue_script('full-admin-copy', $baseUrl . 'js/admin-copy.js', ['jquery'], $version, true);
  }

  public function generateFromText(): void
  {
    $bot = new TextGenerator(
      'text',
      filter_input(INPUT_POST, 'model-text-input') ?? ''
    );

    $bot->addToQueue();

    wp_send_json_success();
  }
}

Hooks::attach();
