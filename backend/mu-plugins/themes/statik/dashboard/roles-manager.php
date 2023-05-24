<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/** Load declared roles */
\array_map(static fn (string $file) => require_once $file, \glob(__DIR__ . '/roles/*.php'));

\add_action('admin_init', 'statik_valid_access', 1);

\add_action('map_meta_cap', 'statik_allows_unfiltered_html', 1, 2);

if (false === \function_exists('statik_valid_access')) {
    /**
     * Valid page access based on role capabilities.
     */
    function statik_valid_access(): void
    {
        global $menu, $submenu, $parent_file, $pagenow;

        /** @var WP_User $user */
        $user = \wp_get_current_user();

        if (false === $user->has_cap('statik-custom-access')) {
            return;
        }

        if (
            (null !== $parent_file && \statik_has_blocked_page_access($parent_file))
            || \statik_has_blocked_page_access($pagenow)
        ) {
            \wp_die(\__('Sorry, you are not allowed to access this page.', 'statik-luna'));
        }

        foreach ($menu ?? [] as $index => $single) {
            if (\statik_has_blocked_page_access($single[2])) {
                unset($menu[$index]);
            }

            foreach ($submenu[$single[2]] ?? [] as $index2 => $single2) {
                if (\statik_has_blocked_page_access($single2[2])) {
                    unset($submenu[$single[2]][$index2]);
                }

                if ($_GET['page'] ?? null) {
                    $page = \filter_input(\INPUT_GET, 'page');

                    if (\statik_has_blocked_page_access($page)) {
                        unset($submenu[$single[2]][$index2]);
                    }
                }
            }
        }
    }
}

if (false === \function_exists('statik_has_blocked_page_access')) {
    /**
     * Check if user has blocked access to the page.
     */
    function statik_has_blocked_page_access(string $page): bool
    {
        static $user;

        if (null === $user) {
            /** @var WP_User $user */
            $user = \wp_get_current_user();
        }

        $pageWithoutParams = \explode('?', $page)[0];

        return (
            \array_key_exists("statik-access-{$page}", $user->allcaps)
            && false === $user->allcaps["statik-access-{$page}"]
        ) || (
            $pageWithoutParams !== $page
            && \array_key_exists("statik-access-{$pageWithoutParams}", $user->allcaps)
            && false === $user->allcaps["statik-access-{$pageWithoutParams}"]
        );
    }
}

if (false === \function_exists('statik_allows_unfiltered_html')) {
    /**
     * Determinate allow save unfiltered HTML in the dashboard.
     * In the Multisite instance only Super Admin have capabilities to
     * do that, so have to done that by ACF filter.
     */
    function statik_allows_unfiltered_html(array $caps, string $cap): array
    {
        $user = \wp_get_current_user();

        if (
            'unfiltered_html' === $cap
            && \is_multisite()
            && (
                \in_array('statik-managed-client', $user->roles)
                || \in_array('administrator', $user->roles)
            )
            && false === (\defined('DISALLOW_UNFILTERED_HTML') && DISALLOW_UNFILTERED_HTML)
        ) {
            $caps = \array_filter($caps, static fn ($cap) => 'do_not_allow' !== $cap);
        }

        return $caps;
    }
}
