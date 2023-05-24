<?php
/**
 * Plugin Name: Statik Mega Menu
 * Plugin URI:  https://statik.space
 * Description: Access WordPress Menus by REST API.
 * Version:     3.10.0
 * Text domain: statik-menu
 * Domain Path: /languages
 * Author:      Statik LTD
 * Author URI:  https://statik.space/
 * License:     GPLv3 or later.
 */

declare(strict_types=1);

namespace Statik\Menu;

\defined('WPINC') || exit;

require_once __DIR__ . '/vendor/autoload.php';

\define('Statik\Menu\VERSION', '3.10.0');
\define('Statik\Menu\PLUGIN_DIR', \plugin_dir_path(__FILE__));
\define('Statik\Menu\PLUGIN_URL', \plugin_dir_url(__FILE__));
\define('Statik\Menu\DEVELOPMENT', \defined('STATIK_MENU_DEVELOPMENT') && STATIK_MENU_DEVELOPMENT === true);

new Plugin(__FILE__);
