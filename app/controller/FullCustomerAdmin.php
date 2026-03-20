<?php

use Full\Customer\License;

defined('ABSPATH') || exit;

class FullCustomerAdmin
{
  public function __construct()
  {
    add_action('admin_init', [$this, 'forceLicenseCheck']);
    add_action('admin_menu', [$this, 'addMenuPage']);

    add_action('admin_enqueue_scripts', [$this, 'assets']);
  }

  public function forceLicenseCheck(): void
  {
    if (filter_input(INPUT_GET, 'full') === 'verify_license') {
      License::updateStatus();

      wp_safe_redirect(esc_url(remove_query_arg('full')));
      exit;
    }

    if (filter_input(INPUT_GET, 'full') === 'repo_clear') {
      @unlink(FullCustomerUpdate::repositoryFilename());

      global $wpdb;

      $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%full/plugin-license%'");

      wp_safe_redirect(esc_url(remove_query_arg('full')));
      exit;
    }
  }

  public function addMenuPage(): void
  {
    $full = fullCustomer();

    add_menu_page(
      $full->getBranding('admin-page-name', 'FULL.services'),
      $full->getBranding('admin-page-name', 'FULL.services'),
      'manage_options',
      'full-connection',
      'fullGetAdminPageView',
      'data:image/svg+xml;base64,' . base64_encode(fullFileSystem()->get_contents(plugin_dir_url(FULL_CUSTOMER_FILE) . 'app/assets/img/menu-novo.svg')),
      0
    );

    $connectionOk   = isFullConnected();
    $cls = $connectionOk ? 'success' : 'error';
    $text = $connectionOk ? 'conectado' : 'desconectado';

    add_submenu_page(
      'full-connection',
      'Conexão',
      'Conexão <span class="full-badge full-' . $cls . '">' . $text . '</span>',
      'manage_options',
      'full-connection',
      'fullGetAdminPageView'
    );

    add_submenu_page(
      'full-connection',
      'Planos',
      'Planos',
      'manage_options',
      'full-store',
      'fullGetAdminPageView'
    );

    $widgets = apply_filters('full-customer/active-widgets-menu', []);

    uasort($widgets, function ($a, $b) {
      return strcmp($a['name'], $b['name']);
    });

    foreach ($widgets as $widget) :
      add_submenu_page(
        'full-connection',
        $widget['name'],
        $widget['name'],
        'manage_options',
        $widget['endpoint'],
        'fullGetAdminPageView'
      );
    endforeach;
  }

  public function assets(): void
  {
    $version = getFullAssetsVersion();
    $baseUrl = trailingslashit(plugin_dir_url(FULL_CUSTOMER_FILE)) . 'app/assets/';

    if (isFullsAdminPage()) :
      wp_enqueue_style('full-icons', 'https://painel.full.services/wp-content/plugins/full/app/assets/vendor/icon-set/style.css', [], '1.0.0');
      wp_enqueue_style('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.css', [], '11.4.35');
      wp_enqueue_style('full-flickity', $baseUrl . 'vendor/flickity/flickity.min.css', [], '2.3.0');
      wp_enqueue_style('full-magnific-popup', $baseUrl . 'vendor/magnific-popup/magnific-popup.min.css', [], '1.0.0');
      wp_enqueue_style('full-admin', $baseUrl . 'css/admin.css', [], $version);

      wp_enqueue_script('full-swal', $baseUrl . 'vendor/sweetalert/sweetalert2.min.js', ['jquery'], '11.4.35', true);
      wp_enqueue_script('full-flickity', $baseUrl . 'vendor/flickity/flickity.min.js', ['jquery'], '2.3.0', true);
      wp_enqueue_script('full-magnific-popup', $baseUrl . 'vendor/magnific-popup/magnific-popup.min.js', ['jquery'], '1.0.0', true);
    endif;

    if ('full-activation' === filter_input(INPUT_GET, 'page')) {
      wp_enqueue_script('full-activation', $baseUrl . 'js/activation.js', ['jquery'], uniqid(), true);
    }

    wp_enqueue_style('full-global-admin', $baseUrl . 'css/global-admin.css', [], $version);
    wp_enqueue_script('full-admin', $baseUrl . 'js/admin.js', ['jquery'], $version, true);
    wp_localize_script('full-admin', 'FULL', fullGetLocalize());
  }
}

new FullCustomerAdmin();
