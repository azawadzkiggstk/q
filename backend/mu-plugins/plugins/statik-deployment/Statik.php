<?php
/**
 * Plugin Name: Statik Deployment
 * Plugin URI:  https://statik.space/
 * Description: Statik plugin for deployment JAMStack infrastructure.
 * Version:     4.0.3
 * Text domain: statik-deployment
 * Domain Path: /languages
 * Author:      Statik LTD
 * Author URI:  https://statik.space/
 * License:     GPLv3 or later.
 */

declare(strict_types=1);

namespace Statik\Deploy;

\defined('WPINC') || exit;

require_once __DIR__ . '/vendor/autoload.php';

\define('Statik\Deploy\VERSION', '4.0.3');
\define('Statik\Deploy\PLUGIN_DIR', \plugin_dir_path(__FILE__));
\define('Statik\Deploy\PLUGIN_URL', \plugin_dir_url(__FILE__));
\define('Statik\Deploy\DEVELOPMENT', \defined('STATIK_DEPLOY_DEVELOPMENT') && STATIK_DEPLOY_DEVELOPMENT === true);
\define('Statik\Deploy\USE_DEFAULT_SETTINGS', \defined('STATIK_DEPLOY_SETTINGS') && STATIK_DEPLOY_SETTINGS);
\define('Statik\Deploy\DEFAULT_SETTINGS', USE_DEFAULT_SETTINGS ? STATIK_DEPLOY_SETTINGS : []);

new Plugin(__FILE__);
