<?php
/**
 * Plugin Name: Statik Protect Themes
 * Description: Protect main Statik infrastructure themes from being disabled.
 * Version:     3.0.0
 * Text domain: statik-themes
 * Author:      Statik LTD
 * Author URI:  https://statik.space/
 * License:     GPLv3 or later.
 */

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

if (\wp_installing()) {
    return;
}

/**
 * Load functionalities when theme is already setup.
 */
\add_action('after_setup_theme', static function (): void {
    include_once __DIR__ . '/themes/statik/functions.php';
}, 0);
