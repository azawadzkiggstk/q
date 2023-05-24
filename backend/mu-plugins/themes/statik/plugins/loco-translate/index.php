<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

add_filter('loco_plugins_data', 'statik_loco_plugins_data');

if (false === \function_exists('statik_loco_plugins_data')) {
    function statik_loco_plugins_data(array $plugins): array
    {
        global $statik;

        return array_merge($plugins, $statik['protected_plugins']);
    }
}