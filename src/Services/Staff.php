<?php

namespace FC\Services;

use FC\FileSystem;
use FC\SkillRepository;
use FC\Skills\ActivateProPlugin;
use FC\Skills\Connect;
use FC\User;

class Staff
{
  private array $manifest = [];

  public function __construct()
  {
    add_action('admin_enqueue_scripts', [$this, 'assets'], PHP_INT_MAX);
    add_action('admin_head', [$this, 'localize'], 0);
    add_action('admin_footer', [$this, 'modal']);
  }

  public function modal(): void
  {
    if (current_user_can('manage_options')) {
      FileSystem::instance()->include('views/components/modal-staff.php');
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
      'authorizationCookies' => base64_encode(wp_json_encode($this->getCurrentCookies())),
    ]);

    echo '</script>';
  }

  public function assets(): void
  {
    if (!current_user_can('manage_options')) {
      return;
    }

    if (defined('FULL_CUSTOMER_DEV') && FULL_CUSTOMER_DEV) {
      wp_enqueue_script_module('vite-client', 'http://localhost:5173/@vite/client', [], null);

      wp_enqueue_style('fc-staff', $this->getViteAssetUrl('assets/scss/staff.scss'), [], null);
      wp_enqueue_script_module('fc-staff', $this->getViteAssetUrl('assets/js/staff.js'), [], null);
    } else {
      wp_enqueue_style('fc-staff', $this->getViteAssetUrl('assets/scss/staff.scss'), [], FULL_CUSTOMER_VERSION);
      wp_enqueue_script_module('fc-staff', $this->getViteAssetUrl('assets/js/staff.js'), [], FULL_CUSTOMER_VERSION);
    }
  }

  private function getCurrentCookies(): array
  {
    $cookies = [];
    foreach ($_COOKIE as $name => $value) {
      $cookies[] = [
        'name'  => (string) $name,
        'value' => (string) $value,
        'domain' => $_SERVER['HTTP_HOST'],
        'path'   => '/'
      ];
    }
    return $cookies;
  }
}
