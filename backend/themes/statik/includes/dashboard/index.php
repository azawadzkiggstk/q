<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * In this file can be added more dashboard filters and actions, that will
 * modify the dashboard behaviour. In child theme all parent's functions can
 * be override or removed from WordPress hooks.
 */
\add_filter('Statik\Luna\mediaUploadLimits', 'statik_set_media_upload_limits');

\add_action('after_setup_theme', 'statik_enqueue_editor_styles');

\add_action('enqueue_block_editor_assets', 'statik_enqueue_editor_page_styles');

\add_action('init', 'statik_register_custom_gutenberg_patterns');

\add_action('after_setup_theme', 'statik_load_theme_text_domain');

/**
 * Function set list of file MIME types with restricted upload possibilities.
 *
 * @see   https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types
 *
 * Values can be defined for all MIME families like: ['image' => 2 * MB_IN_BYTES].
 * In this case, users can only upload files smaller than 2MB for all MIME types
 * from the `image` family.
 *
 * Families can be also split into separate MIME types, for example, for MIME type
 * `application/pdf` should be declared ['application' => ['pdf' => 10 * MB_IN_BYTES]].
 * Then all PDF files will be limited to 10MB.
 *
 * For MIME types that are not included in this array the default max upload size value applied.
 *
 * @param array $limits list of current limits
 */
function statik_set_media_upload_limits(array $limits): array
{
    return \array_merge($limits, ['application' => ['pdf' => 10 * MB_IN_BYTES], 'image' => 2 * MB_IN_BYTES]);
}

/**
 * Enqueue custom editor styles.
 */
function statik_enqueue_editor_styles(): void
{
    $file = \glob(STATIK_THEME_DIR . '/assets/build/editor-*.min.css');

    if (empty($file)) {
        return;
    }

    \add_editor_style(\str_replace(STATIK_THEME_DIR, '', \reset($file)));
}

/**
 * Enqueue custom editor's page styles.
 */
function statik_enqueue_editor_page_styles(): void
{
    $file = \glob(STATIK_THEME_DIR . '/assets/build/editor-page-*.min.css');

    if (empty($file)) {
        return;
    }

    \wp_enqueue_style(
        'statik-editor-page-styles',
        \str_replace(STATIK_THEME_DIR, STATIK_THEME_URL, \reset($file)),
        [],
        STATIK_THEME_VERSION
    );
}

/**
 * Register custom Gutenberg editor patterns.
 */
function statik_register_custom_gutenberg_patterns(): void
{
    \array_map(
        static fn (string $file) => require_once $file,
        \glob(STATIK_THEME_DIR . '/patterns/categories/*.php')
    );
}

/**
 * Load theme text domain.
 */
function statik_load_theme_text_domain(): void
{
    \load_theme_textdomain('statik-luna', STATIK_THEME_DIR . '/languages');
}
