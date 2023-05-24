<?php

declare(strict_types=1);

namespace Statik\Blocks;

use Illuminate\Support\Arr;
use Statik\Blocks\Common\Config\AbstractConfig;

/**
 * Class Config.
 */
class Config extends AbstractConfig
{
    /**
     * {@inheritdoc}
     */
    public static function isDefaultSettings(?string $offset): bool
    {
        if ('settings.core_blocks.value' === $offset) {
            return false === DI::coreBlocksEnabled();
        }

        return USE_DEFAULT_SETTINGS && Arr::has(self::getDefaultSettings(), $offset);
    }

    /**
     * {@inheritdoc}
     */
    public static function getDefaultSettings(): array
    {
        static $defaultSettings;

        $defaultSettings ??= \is_array(DEFAULT_SETTINGS)
            ? (array) DEFAULT_SETTINGS
            : (\json_decode((string) DEFAULT_SETTINGS, true) ?: []);

        return $defaultSettings;
    }
}
