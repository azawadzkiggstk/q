<?php

declare(strict_types=1);

namespace Statik\Blocks\BlockType;

/**
 * Interface SettingsInterface.
 */
interface SettingsInterface
{
    /**
     * Settings fields.
     */
    public function getSettingsFields(): array;

    /**
     * Get settings fields structure.
     */
    public function getSettingsSchema(): array;

    /**
     * Get settings fields values.
     */
    public function getSettings(string $key = null, mixed $default = null): mixed;
}
