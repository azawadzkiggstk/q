<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * Add support for `People` custom post type, which lists all individuals
 * like company employees to be used in different parts of the website.
 */
require_once __DIR__ . '/people/people-cpt.php';
require_once __DIR__ . '/people/people-category-taxonomy.php';
require_once __DIR__ . '/people/people-tag-taxonomy.php';
require_once __DIR__ . '/people/people-custom-fields.php';

/**
 * Add support for `Documents` custom post type, which lists all
 * files to be used in different parts of the website.
 */
require_once __DIR__ . '/documents/documents-cpt.php';
require_once __DIR__ . '/documents/documents-category-taxonomy.php';
require_once __DIR__ . '/documents/documents-tag-taxonomy.php';
require_once __DIR__ . '/documents/documents-custom-fields.php';

/**
 * Add support for `Video` custom post type, which lists all
 * videos to be used in different parts of the website.
 */
require_once __DIR__ . '/videos/videos-cpt.php';
require_once __DIR__ . '/videos/videos-category-taxonomy.php';
require_once __DIR__ . '/videos/videos-tag-taxonomy.php';
require_once __DIR__ . '/videos/videos-custom-fields.php';
