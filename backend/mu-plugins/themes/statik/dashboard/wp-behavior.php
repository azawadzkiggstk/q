<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * Remove canonical redirects, we don't want to redirect to any other page.
 */
\remove_filter('template_redirect', 'redirect_canonical');

/**
 * Change the logo URL in the login page.
 */
\add_filter('login_headerurl', 'home_url');

\add_filter('wp_insert_attachment_data', 'statik_remove_media_parent_id', 10, 4);

\add_action('template_redirect', 'statik_homepage_redirection', 100);

\add_action('init', 'statik_disable_supports_from_cpt', \PHP_INT_MAX);

\add_filter('template_include', 'statik_homepage_template');

\add_filter('wp_die_handler', 'statik_wp_die_template');

if (false === \function_exists('statik_remove_media_parent_id')) {
    /**
     * Filters attachment post data before it is updated in or added to the database.
     */
    function statik_remove_media_parent_id(array $data): array
    {
        $data['post_parent'] = 0;

        return $data;
    }
}

if (false === \function_exists('statik_homepage_redirection')) {
    /**
     * Redirects all calls internal calls to a front page. WordPress should not
     * be called in any way except WP Rest.
     */
    function statik_homepage_redirection(): void
    {
        if (\is_admin() || \is_front_page() || false === \is_user_logged_in()) {
            return;
        }

        $id = \get_queried_object_id();

        0 === $id && \wp_redirect(\home_url());
        \is_singular() && \wp_redirect(\get_edit_post_link($id, 'edit'));
        \is_category() && \wp_redirect(\get_edit_term_link($id));
        \is_author() && \wp_redirect(\get_edit_user_link($id));

        exit;
    }
}

if (false === \function_exists('statik_disable_supports_from_cpt')) {
    /**
     * Remove Comments and Trackbacks supports from all Custom Post Types.
     */
    function statik_disable_supports_from_cpt(): void
    {
        \array_map(static function (string $type): void {
            \remove_post_type_support($type, 'comments');
            \remove_post_type_support($type, 'trackbacks');
        }, \get_post_types());
    }
}

if (false === \function_exists('statik_homepage_template')) {
    /**
     * Override theme homepage template.
     */
    function statik_homepage_template(): string
    {
        return __DIR__ . '/../home.php';
    }
}

if (false === \function_exists('statik_wp_die_template')) {
    /**
     * Modify the default `wp_die` handler.
     */
    function statik_wp_die_template(): callable
    {
        return function (mixed $message, mixed $title = '', array $args = []): void {
            include_once __DIR__ . '/../wp-error.php';

            exit;
        };
    }
}
