<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_filter('site_health_navigation_tabs', 'statik_add_health_check_tab');

\add_action('site_health_tab_content', 'statik_health_check_tab_content');

if (false === \function_exists('statik_add_health_check_tab')) {
    function statik_add_health_check_tab(array $tabs): array
    {
        $tabs['statik'] = \__('Statik infrastructure', 'statik-luna');

        return $tabs;
    }
}

if (false === \function_exists('statik_health_check_tab_content')) {
    function statik_health_check_tab_content(string $tab): void
    {
        if ('statik' !== $tab) {
            return;
        }

        include __DIR__ . '/health-check/statik-tab.php';
    }
}
