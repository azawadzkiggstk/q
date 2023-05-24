<?php

declare(strict_types=1);

namespace Statik\GraphQL\Common;

/**
 * Class Common.
 */
class Common
{
    private static string $VERSION = '3.9.0';

    /**
     * Get Common version.
     */
    public static function version(): string
    {
        return self::$VERSION;
    }

    /**
     * Get Common directory.
     */
    public static function dir(string $path = ''): string
    {
        $path = \str_starts_with($path, '/') ? $path : "/{$path}";
        $dir = \rtrim(\dirname(__DIR__), '/\\');

        return \wp_normalize_path($dir . $path);
    }

    /**
     * Get Common directory.
     */
    public static function url(string $path = ''): string
    {
        $path = \str_starts_with($path, '/') ? $path : "/{$path}";
        $base = \defined('WP_CONTENT_URL') && \defined('WP_CONTENT_DIR')
            ? WP_CONTENT_URL . \str_replace(\wp_normalize_path(WP_CONTENT_DIR), '', self::dir())
            : '';

        return \untrailingslashit($base) . $path;
    }

    /**
     * Determinate if debug is enabled.
     */
    public static function development(): bool
    {
        return \defined('STATIK_COMMON_DEVELOPMENT') && STATIK_COMMON_DEVELOPMENT === true;
    }
}
