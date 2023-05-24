<?php

declare(strict_types=1);

namespace Statik\Deploy\Common\Config;

/**
 * Interface ConfigInterface.
 */
interface ConfigInterface
{
    /**
     * Check if Settings key exists in constant with default settings.
     */
    public static function isDefaultSettings(?string $offset): bool;

    /**
     * Check get Array with default settings.
     */
    public static function getDefaultSettings(): array;

    /**
     * Save all Config in the database using the Driver mechanism.
     */
    public function save(): bool;

    /**
     * Force flush all records that have an expiration value.
     */
    public function flushExpirations(): bool;

    /**
     * Get value from Config using a key.
     */
    public function get(?string $offset, mixed $default = null): mixed;

    /**
     * Get settings value from Config using a key.
     */
    public function getValue(?string $offset, mixed $default = null): mixed;

    /**
     * Get keys value from Config using a key.
     */
    public function getKeys(?string $offset): ?array;

    /**
     * Set value in Config by key.
     */
    public function set(string $offset, mixed $value): bool;

    /**
     * Check if value exists in Config.
     */
    public function has(string $offset): bool;

    /**
     * Delete value from Config.
     */
    public function delete(string $offset): bool;

    /**
     * Return the last element in an array passing a given truth test.
     */
    public function last(string $offset, mixed $default = null): mixed;

    /**
     * Prepend an item in config tree.
     */
    public function prepend(string $offset, mixed $value, bool $unique = false): bool;

    /**
     * Return all Config as a array.
     */
    public function toArray(bool $flatten = false): array;
}
