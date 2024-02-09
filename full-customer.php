<?php defined('ABSPATH') || exit;

/**
 * Plugin Name:         FULL - Cliente
 * Description:         Este plugin adiciona novas extensões úteis e conecta-o ao painel da FULL. para ativações de outros plugins.
 * Version:             3.0.7
 * Requires at least:   6.2
 * Tested up to:        6.4.2
 * Requires PHP:        7.4
 * Author:              FULL.
 * Author URI:          https://full.services/
 * License:             GPL v3 or later
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:         full-customer
 * Domain Path:         /app/i18n
 */

if (!defined('FULL_CUSTOMER_VERSION')) :
  define('FULL_CUSTOMER_VERSION', '3.0.7');
  define('FULL_CUSTOMER_FILE', __FILE__);
  define('FULL_CUSTOMER_APP', __DIR__ . '/app');
  define('FULL_BACKUP_TIME_LIMIT', 900);
  require_once FULL_CUSTOMER_APP . '/init.php';
endif;
