<?php defined('ABSPATH') || exit;

/**
 * Plugin Name:         FULL - Customer
 * Description:         This plugin allows automatic installation and activation of plugins purchased from FULL.
 * Version:             1.0.7
 * Requires at least:   5.6
 * Requires PHP:        7.2
 * Author:              FULL.
 * Author URI:          https://full.services/
 * License:             GPL v3 or later
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:         full-customer
 * Domain Path:         /app/i18n
 */

if (!defined('FULL_CUSTOMER_VERSION')) :
  define('FULL_CUSTOMER_VERSION', '1.0.7');
  define('FULL_CUSTOMER_FILE', __FILE__);
  define('FULL_CUSTOMER_APP', __DIR__ . '/app');
  define('FULL_BACKUP_TIME_LIMIT', 900);
  require_once FULL_CUSTOMER_APP . '/init.php';
endif;
