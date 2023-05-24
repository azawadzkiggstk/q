<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

if (false === \class_exists('ACF')) {
    return;
}

\add_action('init', 'statik_initialize_acf_options', 5);

if (false === \function_exists('statik_initialize_acf_options')) {
    /**
     * Initialise and add fields to ACF settings.
     */
    function statik_initialize_acf_options(): void
    {
        if (false === \function_exists('acf_add_options_page')) {
            return;
        }

        \acf_add_options_page([
            'page_title' => \__('Statik theme settings', 'statik-luna'),
            'menu_title' => \__('Statik settings', 'statik-luna'),
            'menu_slug' => 'statik_theme_settings',
            'capability' => 'edit_posts',
            'redirect' => true,
            'show_in_graphql' => true,
            'graphql_field_name' => 'allStatikSettings',
            'icon_url' => 'dashicons-paperclip',
            'position' => 60,
        ]);
    }
}

/**
 * Load options pages.
 */
require_once __DIR__ . '/global-options.php';
require_once __DIR__ . '/cookie-options.php';
require_once __DIR__ . '/footer-options.php';