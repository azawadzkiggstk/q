<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_action('admin_enqueue_scripts', 'statik_enqueue_dashboard_styles');

\add_action('login_enqueue_scripts', 'statik_enqueue_dashboard_styles');

\add_action('admin_init', 'statik_admin_color_scheme');

\add_action('init', 'statik_register_menus');

\add_action('after_setup_theme', 'statik_init_theme_features');

\add_action('after_setup_theme', 'statik_load_protected_theme_text_domain');

if (false === \function_exists('statik_enqueue_dashboard_styles')) {
    /**
     * Enqueue default styles to WordPress dashboard.
     */
    function statik_enqueue_dashboard_styles(): void
    {
        \wp_enqueue_style(
            'statik-dashboard',
            \plugin_dir_url(__FILE__) . 'stylesheets/dashboard.css',
            [],
            \wp_get_theme()->get('Version')
        );
    }
}

if (false === \function_exists('statik_register_menus')) {
    /**
     * Register navigation menus uses wp_nav_menu in five places.
     */
    function statik_register_menus(): void
    {
        \register_nav_menus(
            [
                'primary' => \__('Desktop Menu', 'statik-luna'),
                'mobile' => \__('Mobile Menu', 'statik-luna'),
                'footer' => \__('Footer Menu', 'statik-luna'),
                'sub-footer' => \__('Sub-footer Menu', 'statik-luna'),
            ]
        );
    }
}

if (false === \function_exists('statik_init_theme_features')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function statik_init_theme_features(): void
    {
        // Editor font sizes
        \add_theme_support(
            'editor-font-sizes',
            [
                [
                    'name' => \_x('Small', 'Name of the small font size in the block editor', 'statik-luna'),
                    'shortName' => \_x('S', 'Short name of the small font size in the block editor.', 'statik-luna'),
                    'size' => 13,
                    'slug' => 'small',
                ],
                [
                    'name' => \_x('Regular', 'Name of the regular font size in the block editor', 'statik-luna'),
                    'shortName' => \_x('M', 'Short name of the regular font size in the block editor.', 'statik-luna'),
                    'size' => 16,
                    'slug' => 'normal',
                ],
                [
                    'name' => \_x('Large', 'Name of the large font size in the block editor', 'statik-luna'),
                    'shortName' => \_x('L', 'Short name of the large font size in the block editor.', 'statik-luna'),
                    'size' => 24,
                    'slug' => 'large',
                ],
                [
                    'name' => \_x('Larger', 'Name of the larger font size in the block editor', 'statik-luna'),
                    'shortName' => \_x('XL', 'Short name of the larger font size in the block editor.', 'statik-luna'),
                    'size' => 32,
                    'slug' => 'larger',
                ],
            ]
        );

        // Editor extra features
        \add_theme_support('editor-styles');
        \add_theme_support('align-wide');
        \add_theme_support('post-thumbnails');
        \add_theme_support('custom-spacing');
        \add_theme_support('title-tag');
        \remove_theme_support('core-block-patterns');

        // Add extra image sizes.
        \set_post_thumbnail_size(1200);
    }
}

if (false === \function_exists('statik_admin_color_scheme')) {
    /**
     * Create Statik dashboard color scheme.
     */
    function statik_admin_color_scheme(): void
    {
        \wp_admin_css_color(
            'fresh',
            \__('Statik', 'statik-luna'),
            \plugin_dir_url(__FILE__) . 'stylesheets/statik/colors.css',
            ['#202134', '#30d993', '#0073aa'],
        );

        // Restore the old one and allow use it as well.
        \wp_admin_css_color(
            'old_fresh',
            \__('Fresh', 'statik-luna'),
            false,
            ['#1d2327', '#2c3338', '#2271b1', '#72aee6'],
            ['base' => '#a7aaad', 'focus' => '#72aee6', 'current' => '#fff']
        );
    }
}

if (false === \function_exists('statik_load_protected_theme_text_domain')) {
    /**
     * Load theme text domain.
     */
    function statik_load_protected_theme_text_domain(): void
    {
        \load_theme_textdomain('statik', __DIR__ . '/../languages');
    }
}
