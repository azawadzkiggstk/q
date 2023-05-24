<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

if (false === \defined('STATIK_API_ENDPOINT') || false === \defined('STATIK_API_TOKEN')) {
    \wp_die(\__('Missing required STATIK_API_ENDPOINT or STATIK_API_TOKEN constants!', 'statik-luna'));
}

/**
 * Load Custom post types, by default there are two CPTs: People and Documents.
 */
require_once __DIR__ . '/custom-post-types/index.php';

/**
 * Load all Dashboard internal integrations such extra users roles or plugins updates interface.
 */
require_once __DIR__ . '/dashboard/index.php';

/**
 * Load all custom Plugins integrations.
 */
require_once __DIR__ . '/plugins/index.php';
