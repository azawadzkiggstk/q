<?php
/**
 * Plugin Name: JSON Basic Authentication
 * Description: Basic Authentication handler for the JSON API, used for development and debugging purposes.
 * Version:     3.0.0
 * Text domain: statik
 * Author:      Statik LTD
 * Author URI:  https://statik.space/
 * License:     GPLv3 or later.
 */

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_filter('determine_current_user', 'statik_json_basic_auth_handler', 20);

/**
 * Json basic auth handler.
 */
function statik_json_basic_auth_handler(?int $userId): ?int
{
    if (false === empty($userId)) {
        return $userId;
    }

    if (false === isset($_SERVER['PHP_AUTH_USER'])) {
        return $userId;
    }

    /**
     * In multi-site, wp_authenticate_spam_check filter is run on authentication. This filter calls
     * wp_get_current_user which in turn calls the determine_current_user filter. This leads to infinite
     * recursion and a stack overflow unless the current function is removed from the determine_current_user
     * filter during authentication.
     */
    \remove_filter('determine_current_user', __FUNCTION__, 20);

    $user = \wp_authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

    \add_filter('determine_current_user', __FUNCTION__, 20);

    if (\is_wp_error($user)) {
        return null;
    }

    return $user->ID;
}
