<?php

declare(strict_types=1);

namespace Statik\Menu;

use Pimple\Container;

/**
 * Class DI.
 */
class DI
{
    /**
     * Get object from DI container.
     */
    public static function get(string $key): mixed
    {
        return $GLOBALS[DIProvider::CONTAINER_NAME]->offsetGet($key);
    }

    /**
     * Dependency Injection container.
     */
    public static function container(): Container
    {
        return $GLOBALS[DIProvider::CONTAINER_NAME];
    }

    /**
     * Get Plugin version.
     */
    public static function version(): string
    {
        return VERSION;
    }

    /**
     * Is Development Enabled.
     */
    public static function development(): bool
    {
        return DEVELOPMENT;
    }

    /**
     * Get Plugin directory.
     */
    public static function dir(?string $path = null): string
    {
        return ($path && \str_starts_with('/', $path) ? \untrailingslashit(PLUGIN_DIR) : PLUGIN_DIR) . $path;
    }

    /**
     * Get Plugin URL.
     */
    public static function url(?string $path = null): string
    {
        return ($path && \str_starts_with('/', $path) ? \untrailingslashit(PLUGIN_URL) : PLUGIN_URL) . $path;
    }
}
