<?php

declare(strict_types=1);

namespace Statik\GraphQL;

use Illuminate\Support\Arr;
use Statik\GraphQL\Common\Config\AbstractConfig;

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
        if (\defined('GRAPHQL_DEBUG') && 'settings.debug.value' === $offset) {
            return true;
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

        if (\defined('GRAPHQL_DEBUG')) {
            $defaultSettings = \array_merge($defaultSettings, ['settings.debug.value' => GRAPHQL_DEBUG]);
        }

        return $defaultSettings;
    }
}
