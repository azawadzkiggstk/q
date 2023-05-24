<?php
/**
 * Plugin Name: Statik Gutenberg Blocks
 * Plugin URI:  https://statik.space/
 * Description: Statik plugin for manage Gutenberg Blocks.
 * Version:     4.13.0
 * Text domain: statik-blocks
 * Domain Path: /languages
 * Author:      Statik LTD
 * Author URI:  https://statik.space/
 * License:     GPLv3 or later.
 */

declare(strict_types=1);

namespace Statik\Blocks;

\defined('WPINC') || exit;

require_once __DIR__ . '/vendor/autoload.php';

\define('Statik\Blocks\VERSION', '4.13.0');
\define('Statik\Blocks\ECOSYSTEM', true);
\define('Statik\Blocks\PLUGIN_DIR', \plugin_dir_path(__FILE__));
\define('Statik\Blocks\PLUGIN_URL', \plugin_dir_url(__FILE__));
\define('Statik\Blocks\DEVELOPMENT', \defined('STATIK_BLOCKS_DEVELOPMENT') && STATIK_BLOCKS_DEVELOPMENT === true);
\define('Statik\Blocks\USE_DEFAULT_SETTINGS', \defined('STATIK_BLOCKS_SETTINGS') && STATIK_BLOCKS_SETTINGS);
\define('Statik\Blocks\DEFAULT_SETTINGS', USE_DEFAULT_SETTINGS ? STATIK_BLOCKS_SETTINGS : []);

new Plugin(__FILE__);
