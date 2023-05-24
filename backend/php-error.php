<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * This is a custom PHP error handler. Instead of displaying a full error
 * user should see a message, that the website is temporarily unavailable.
 */
require __DIR__ . '/mu-plugins/themes/statik/php-error.php';
