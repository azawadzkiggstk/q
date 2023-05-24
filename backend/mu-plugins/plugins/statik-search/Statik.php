<?php
/**
 * Plugin Name: Statik Search
 * Plugin URI:  https://statik.space
 * Description: A WordPress plugin for easier integration with Statik Search service powered by ElasticSearch.
 * Version:     4.0.0
 * Text domain: statik-search
 * Domain Path: /languages
 * Author:      Statik LTD
 * Author URI:  https://statik.space/
 * License:     GPLv3 or later.
 */

declare(strict_types=1);

namespace Statik\Search;

\defined('WPINC') || exit;

require_once __DIR__ . '/vendor/autoload.php';

\define('Statik\Search\VERSION', '4.0.0');
\define('Statik\Search\PLUGIN_DIR', \plugin_dir_path(__FILE__));
\define('Statik\Search\PLUGIN_URL', \plugin_dir_url(__FILE__));
\define('Statik\Search\DEVELOPMENT', \defined('STATIK_SEARCH_DEVELOPMENT') && STATIK_SEARCH_DEVELOPMENT === true);
\define('Statik\Search\USE_DEFAULT_SETTINGS', \defined('STATIK_SEARCH_SETTINGS') && STATIK_SEARCH_SETTINGS);
\define('Statik\Search\DEFAULT_SETTINGS', USE_DEFAULT_SETTINGS ? STATIK_SEARCH_SETTINGS : []);

new Plugin(__FILE__);
