<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * Add custom WP dashboard supports and register required extensions.
 */
require_once __DIR__ . '/dashboard.php';

/**
 * Functionality for force blocking upload of some file MIME types.
 * These types have separate maximum allowed file size, for example
 * for protect from uploading to big images.
 */
require_once __DIR__ . '/media-upload-limit-file-size.php';

/**
 * Add roles manager which is responsible for adjusting existing roles
 * to requirements of the Statik Stack. The most important functionality
 * created by this dependency is managed client role which disables
 * pages in the dashboard that could potentially break the website.
 */
require_once __DIR__ . '/roles-manager.php';

/**
 * Add Trigger ping button that allows user to update data from the Statik API.
 */
require_once __DIR__ . '/trigger-ping.php';

/**
 * Modify regular WordPress behavior, override some default functionalities.
 */
require_once __DIR__ . '/wp-behavior.php';

/**
 * Create a new Health Check tab with Statik infrastructure messages.
 */
require_once __DIR__ . '/health-check.php';
