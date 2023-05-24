<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * In this file can be added more external Plugins integrations.
 * Each plugin should be in the separate directory and should
 * be included there using the `require_once` function.
 */

/**
 * Load Advanced Custom Fields plugin integration.
 */
require_once __DIR__ . '/advanced-custom-fields/index.php';