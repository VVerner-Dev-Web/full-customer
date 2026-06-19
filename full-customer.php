<?php

use FC\Root;

defined('ABSPATH') || exit;

/**
 * Plugin Name:         FULL.Cliente
 * Description:         Este plugin adiciona novas extensões úteis e conecta-o ao painel da FULL. para ativações de outros plugins.
 * Version:             4.0.3
 * Requires at least:   6.5
 * Tested up to:        7.0
 * Requires PHP:        7.4
 * Author:              FULL.
 * Author URI:          https://full.services/
 */

if (!defined('FULL_CUSTOMER_VERSION')) {
  define('FULL_CUSTOMER_FILE', __FILE__);
  define('FULL_CUSTOMER_PATH', __DIR__);
  define('FULL_CUSTOMER_REST_NAMESPACE', 'fc/v1');

  if (file_exists(FULL_CUSTOMER_PATH . '/dev.php')) {
    require_once FULL_CUSTOMER_PATH . '/dev.php';
  }

  defined('FULL_CUSTOMER_DEV') || define('FULL_CUSTOMER_DEV', false);
  defined('FULL_CUSTOMER_API_DOMAIN') || define('FULL_CUSTOMER_API_DOMAIN', FULL_CUSTOMER_DEV ? 'https://full.dev/' : 'https://api.full.services/');

  define('FULL_CUSTOMER_VERSION', FULL_CUSTOMER_DEV ? uniqid() : '4.0.3');
  define('FULL_CUSTOMER_API_URL', FULL_CUSTOMER_DEV ? FULL_CUSTOMER_API_DOMAIN . 'wp-json/fc-ai' : FULL_CUSTOMER_API_DOMAIN . 'wp-json/fc-ai');

  require_once FULL_CUSTOMER_PATH . '/vendor/autoload.php';

  (new Root)->init();
}
