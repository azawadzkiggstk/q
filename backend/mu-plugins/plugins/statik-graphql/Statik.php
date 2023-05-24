<?php
/**
 * Plugin Name: Statik GraphQL
 * Plugin URI:  https://statik.space/
 * Description: Statik plugin for manage the GraphQL.
 * Version:     3.20.2
 * Text domain: statik-graphql
 * Domain Path: /languages
 * Author:      Statik LTD
 * Author URI:  https://statik.space/
 * License:     GPLv3 or later.
 */

declare(strict_types=1);

namespace Statik\GraphQL;

\defined('WPINC') || exit;

require_once __DIR__ . '/vendor/autoload.php';

\define('Statik\GraphQL\VERSION', '3.20.2');
\define('Statik\GraphQL\PLUGIN_DIR', \plugin_dir_path(__FILE__));
\define('Statik\GraphQL\PLUGIN_URL', \plugin_dir_url(__FILE__));
\define('Statik\GraphQL\DEVELOPMENT', \defined('STATIK_GRAPHQL_DEVELOPMENT') && STATIK_GRAPHQL_DEVELOPMENT === true);
\define('Statik\GraphQL\USE_DEFAULT_SETTINGS', \defined('STATIK_GRAPHQL_SETTINGS') && STATIK_GRAPHQL_SETTINGS);
\define('Statik\GraphQL\DEFAULT_SETTINGS', USE_DEFAULT_SETTINGS ? STATIK_GRAPHQL_SETTINGS : []);

new Plugin(__FILE__);
