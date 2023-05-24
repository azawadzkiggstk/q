<?php
/**
 * Plugin Name: Statik Multisite Initializer
 * Description: A WordPress plugin for easier initialize new blogs in the Multisite instance.
 * Version:     3.0.0
 * Text domain: statik-multisite
 * Author:      Statik LTD
 * Author URI:  https://statik.space/
 * License:     GPLv3 or later.
 */

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

if (false === \is_multisite()) {
    return;
}

const NETWORK_META_NAME = 'statik_site_initialized';

\add_action('init', static fn () => \statik_init_blog());
\add_action('wp_initialize_site', 'statik_init_blog', 1000);

/**
 * Set default data into database for blog.
 */
function statik_init_blog(WP_Site $site = null): void
{
    global $wpdb;
    $switched = false;

    if (null === $site) {
        $site = \get_blog_details();
    }

    if ($site->id !== \get_current_blog_id()) {
        $switched = \switch_to_blog($site->id);
    }

    if (1 === (int) \get_option(NETWORK_META_NAME)) {
        $switched && \restore_current_blog();

        return;
    }

    $sqlFilePath = WP_CONTENT_DIR . '/mu-plugins/migrations/init.sql';

    if (false === \file_exists($sqlFilePath) || false === \is_readable($sqlFilePath)) {
        \update_user_meta(
            null,
            NETWORK_META_NAME,
            [$site->id => \__('File migrations/init.sql not exists or is not readable.', 'statik-multisite')]
        );

        return;
    }

    $sqlContent = \file_get_contents($sqlFilePath);

    if (false === $sqlContent) {
        \update_user_meta(
            \get_current_user_id(),
            NETWORK_META_NAME,
            [$site->id => \__('File migrations/init.sql could not be read.', 'statik-multisite')]
        );

        return;
    }

    $sqlForBlog = \str_replace(
        [
            '{{ DOMAIN }}',
            '{{ DATE_TIME }}',
            '{{ DB_PREFIX }}',
        ],
        [
            \get_home_url($site->id),
            \date('Y-m-d H:i:s'),
            $wpdb->get_blog_prefix($site->id),
        ],
        $sqlContent
    );

    if (false === $wpdb->use_mysqli) {
        \update_user_meta(
            \get_current_user_id(),
            NETWORK_META_NAME,
            [$site->id => \__('The WordPress database `mysqli` extension is not installed.', 'statik-multisite')]
        );

        return;
    }

    $query = \mysqli_multi_query($wpdb->dbh, $sqlForBlog);

    \update_option(NETWORK_META_NAME, '1', true);

    \update_user_meta(
        \get_current_user_id(),
        NETWORK_META_NAME,
        [$site->id => false !== $query ?: \mysqli_error($wpdb->dbh)]
    );

    \restore_current_blog();

    \add_action('network_admin_notices', 'statik_init_blog_notice', 1000);

    \wp_cache_flush();
}

/**
 * Notice about site initialized.
 */
function statik_init_blog_notice(): void
{
    if (false === isset($_GET['id'])) {
        return;
    }

    $id = \filter_input(\INPUT_GET, 'id', \FILTER_SANITIZE_NUMBER_INT);
    $meta = \get_user_meta(\get_current_user_id(), NETWORK_META_NAME, true);

    if (false === \is_array($meta) || false === \array_key_exists($id, $meta)) {
        return;
    }

    if (true === $meta[$id]) {
        $adminNoticeClass = 'notice-success';
        $adminNotice = \sprintf(
            \__('Statik default settings for blog <strong>%s</strong> site have been installed!', 'statik-multisite'),
            \get_site($id)->blogname
        );
    } else {
        $adminNoticeClass = 'notice-error';
        $adminNotice = \sprintf(
            \__(
                'Statik default settings for blog <strong>%s</strong> site have not been installed! Error: <code>%s</code>.',
                'statik-multisite'
            ),
            \get_site($id)->blogname,
            $meta[$id]
        );
    }

    echo "<div class='notice {$adminNoticeClass} is-dismissible'><p>{$adminNotice}</p></div>";

    \delete_user_meta(\get_current_user_id(), NETWORK_META_NAME);
}
