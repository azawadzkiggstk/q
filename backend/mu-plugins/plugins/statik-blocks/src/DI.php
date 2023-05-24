<?php

declare(strict_types=1);

namespace Statik\Blocks;

use Pimple\Container;
use Statik\Blocks\Block\PostManager;
use Statik\Blocks\Common\Settings\Generator;

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
     * Are Statik Core blocks Enabled.
     */
    public static function coreBlocksEnabled(): bool
    {
        if (false === ECOSYSTEM) {
            return false;
        }

        if (\defined('STATIK_BLOCKS_DISABLE_CORE')) {
            return false === (bool) STATIK_BLOCKS_DISABLE_CORE;
        }

        /**
         * Fire disable core blocks filter.
         *
         * @param bool $disable whether disable blocks
         *
         * @return bool
         */
        return false === (bool) \apply_filters('Statik\Blocks\disableCore', false);
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

    /**
     * Get Blocks Manager object.
     */
    public static function BlocksManager(): BlocksManager
    {
        return static::get('blocksManager');
    }

    /**
     * Get Blocks Manager object.
     */
    public static function PostManager(): PostManager
    {
        return static::get('postManager');
    }
}
