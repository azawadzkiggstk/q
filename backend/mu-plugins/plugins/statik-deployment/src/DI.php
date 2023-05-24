<?php

declare(strict_types=1);

namespace Statik\Deploy;

use Pimple\Container;
use Statik\Deploy\Common\Settings\Generator;

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

    /**
     * Whether preview is enabled.
     */
    public static function isPreview(): bool
    {
        return 'enabled' === self::Config()->getValue('settings.enable_preview');
    }

    /**
     * Get Config object.
     */
    public static function Config(): Config
    {
        return static::get('config');
    }

    /**
     * Get Generator object.
     */
    public static function Generator(): Generator
    {
        return static::get('generator');
    }
}
