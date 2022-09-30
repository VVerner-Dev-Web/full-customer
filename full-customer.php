<?php defined('ABSPATH') || exit;

/**
 * Plugin Name:         FULL - Customer
 * Description:         This plugin allows automatic installation and activation of plugins purchased from FULL.
 * Version:             0.1.2
 * Requires at least:   5.6
 * Requires PHP:        7.2
 * Author:              FULL.
 * Author URI:          https://fullstackagency.club/
 * License:             GPL v3 or later
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:         full-customer
 * Domain Path:         /app/i18n
 */

define('FULL_CUSTOMER_VERSION', '0.1.2');
define('FULL_CUSTOMER_FILE', __FILE__);
define('FULL_CUSTOMER_APP', __DIR__ . '/app');
require_once FULL_CUSTOMER_APP . '/init.php';
