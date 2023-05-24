<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * Load Gravity Forms plugin integration.
 */
require_once __DIR__ . '/gravity-forms/index.php';

/**
 * Load Advanced Custom Fields plugin integration.
 */
require_once __DIR__ . '/advanced-custom-fields/index.php';

/**
 * Load The SEO Framework plugin integration.
 */
require_once __DIR__ . '/the-seo-framework/index.php';

/**
 * Load Loco Translate plugin integration.
 */
require_once __DIR__ . '/loco-translate/index.php';
