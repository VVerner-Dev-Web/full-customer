<?php

namespace FC\Services;

use FC\FileSystem;
use FC\SkillRepository;
use FC\Skills\ActivateProPlugin;
use FC\Skills\Connect;
use FC\User;

class AdminPage
{
  private array $manifest = [];

  public function __construct()
  {
    add_action('admin_menu', [$this, 'register']);
    add_action('admin_enqueue_scripts', [$this, 'assets'], PHP_INT_MAX);
    add_action('admin_head', [$this, 'localize'], 0);
    add_action('in_admin_header', [$this, 'removeNotices'], PHP_INT_MAX);
  }

  public function removeNotices(): void
  {
    if ('full' === filter_input(INPUT_GET, 'page')) {
      remove_all_actions('admin_notices');
      remove_all_actions('all_admin_notices');
    }
  }

  private function getViteAssetUrl(string $entry): string
  {
    if (defined('FULL_CUSTOMER_DEV') && FULL_CUSTOMER_DEV) {
      return "http://localhost:5173/{$entry}";
    }

    if (empty($this->manifest)) {
      $manifest_path = 'assets/dist/.vite/manifest.json';
      $content = FileSystem::instance()->getContents($manifest_path);
      $this->manifest = $content ? json_decode($content, true) : [];
    }

    return isset($this->manifest[$entry])
      ? FileSystem::instance()->getUrl('assets/dist/' . $this->manifest[$entry]['file'])
      : '';
  }

  public function localize(): void
  {
    if (!current_user_can('manage_options')) {
      return;
    }

    echo '<script>';

    echo 'window.fcData = window.fcData || ' . wp_json_encode([
      'connected' => User::instance()->isConnected(),
      'restUrl'   => get_rest_url(null, 'fc/v1'),
      'nonce'     => wp_create_nonce('wp_rest'),
      'baseUrl'   => admin_url('admin.php?page=full'),
      'wpPluginsUrl' => admin_url('plugins.php'),
      'skillsRepository' => SkillRepository::instance()->toArray(),
      'starterSkill' => User::instance()->isConnected() ? ActivateProPlugin::ID : Connect::ID,
      'authorizationCookies' => User::instance()->getCurrentCookies(),
    ]);

    echo '</script>';
  }

  public function register(): void
  {
    add_menu_page(
      'FULL.',
      'FULL.',
      'manage_options',
      'full',
      [$this, 'display'],
      'data:image/svg+xml;base64,' . base64_encode(FileSystem::instance()->getContents('assets/images/logo-icon.svg')),
      3
    );
  }

  public function assets(): void
  {
    if ('full' !== filter_input(INPUT_GET, 'page')) {
      return;
    }

    wp_enqueue_style('fc-bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], null);
    wp_enqueue_script('fc-bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', ['jquery'], null, true);

    if (defined('FULL_CUSTOMER_DEV') && FULL_CUSTOMER_DEV) {
      wp_enqueue_script_module('vite-client', 'http://localhost:5173/@vite/client', [], null);

      wp_enqueue_style('fc-main', $this->getViteAssetUrl('assets/scss/main.scss'), [], null);
      wp_enqueue_script_module('fc-app', $this->getViteAssetUrl('assets/js/app.js'), [], null);
    } else {
      wp_enqueue_style('fc-main', $this->getViteAssetUrl('assets/scss/main.scss'), [], FULL_CUSTOMER_VERSION);
      wp_enqueue_script_module('fc-app', $this->getViteAssetUrl('assets/js/app.js'), [], FULL_CUSTOMER_VERSION);
    }
  }

  public function display(): void
  {
    FileSystem::instance()->include('views/root.php');
  }
}
