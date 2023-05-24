<?php
/*
 * Plugin Name: Statik Protect Plugins
 * Description: Protect main Statik infrastructure plugins from being disabled.
 * Version:     3.0.0
 * Text domain: statik-plugins
 * Author:      Statik LTD
 * Author URI:  https://statik.space/
 * License:     GPLv3 or later.
 */

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

if (\wp_installing()) {
    return;
}

$GLOBALS['statik']['protected_plugins'] = [
    'acf-to-rest-api/class-acf-to-rest-api.php' => true,
    'advanced-custom-fields-pro/acf.php' => true,
    'autodescription/autodescription.php' => true,
    'gravityforms/gravityforms.php' => true,
    'gutenberg/gutenberg.php' => true,
    'statik-blocks/Statik.php' => true,
    'statik-deployment/Statik.php' => true,
    'statik-search/Statik.php' => true,
    'statik-graphql/Statik.php' => true,
    'statik-menu/Statik.php' => true,
];

\add_action('plugins_loaded', 'statik_enable_required_plugins', 0);

/**
 * Enable required plugins.
 */
function statik_enable_required_plugins(): void
{
    /** WordPress Plugin Administration API */
    require_once ABSPATH . 'wp-admin/includes/plugin.php';

    global $statik;

    $action = \filter_input(\INPUT_GET, 'action');
    $actionPlugin = \filter_input(\INPUT_GET, 'plugin');
    $plugins = $statik['protected_plugins'];
    $statik['protected_plugins'] = [];

    foreach ($plugins as $plugin => $enable) {
        $directory = __DIR__ . "/plugins/{$plugin}";

        if (false === \file_exists($directory)) {
            continue;
        }

        $statik['protected_plugins'][$directory] = \get_plugin_data($directory);
        $statik['protected_plugins'][$directory]['Path'] = $plugin;
        $statik['protected_plugins'][$directory]['Enabled'] = false;
        $statik['protected_plugins'][$directory]['basedir'] = __DIR__ . '/plugins/';

        if (false === $enable) {
            continue;
        }

        if (\is_plugin_active($plugin) || \is_plugin_active("dev-{$plugin}")) {
            continue;
        }

        if ('activate' === $action && \in_array($actionPlugin, [$plugin, "dev-{$plugin}"], true)) {
            continue;
        }

        $statik['protected_plugins'][$directory]['Enabled'] = true;

        require_once $directory;
    }
}
