<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('File cannot be opened directly!');

\define('STATIK_THEME_VERSION', \wp_get_theme()->get('Version'));
\define('STATIK_THEME_DIR', \get_template_directory());
\define('STATIK_THEME_URL', \get_template_directory_uri());

/**
 * Load Custom post types.
 */
require_once __DIR__ . '/includes/custom-post-types/index.php';

/**
 * Load all Dashboard internal integrations.
 */
require_once __DIR__ . '/includes/dashboard/index.php';

/**
 * Load all custom Plugins integrations.
 */
require_once __DIR__ . '/includes/plugins/index.php';
