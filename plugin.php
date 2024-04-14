<?php

/**
 * @wordpress-plugin
 * Plugin Name:             Payment Methods Report for WooCommerce
 * Plugin URI:              https://github.com/kingkero/payment-methods-report-woocommerce
 * Description:             Adds a new report to the WooCommerce analytics section about used payment methods.
 * Author:                  kingkero
 * Author URI:              https://www.kero.codes/
 * Text Domain:             payment-methods-report-woocommerce
 * Version:                 1.0.0
 * Requires PHP:            8.1
 * Requires at least:       6.5
 * Requires Plugins:        woocommerce
 *
 * WC requires at least:    6.0.0
 *
 * License:                 GNU General Public License v3.0
 * License URI:             https://www.gnu.org/licenses/gpl-3.0.html
 */

declare(strict_types=1);

use KK\PaymentMethodsReport\Plugin;

// load composer's autoload
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// initialize main class, here all actions and filters are initialized
new Plugin(dirname(__FILE__), plugin_dir_url(__FILE__));
