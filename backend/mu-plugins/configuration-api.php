<?php
/**
 * Plugin Name: Statik configuration API
 * Description: Manage the most important infrastructure configuration.
 * Version: 2.0.0
 * Author: Statik LTD
 * Author URI: https://statik.space/
 * License: GNU General Public License v2 or later.
 */

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * Create Statik REST endpoints required by Statik API.
 */
require_once __DIR__ . '/api/index.php';
