<?php

declare(strict_types=1);

namespace Statik\Deploy;

use Illuminate\Support\Arr;
use Statik\Deploy\Common\Config\AbstractConfig;
use Statik\Deploy\Deployment\Provider\Provider\ProviderInterface;

/**
 * Class Config.
 */
class Config extends AbstractConfig
{
    /**
     * Get Deployment Provider class name. Return name only if Provider implements
     * the `DriverInterface` interface.
     */
    public function getProvider(string $key = 'deployment.provider'): ?string
    {
        $provider = $this->get($key);

        return \is_a($provider, ProviderInterface::class, true) ? (string) $provider : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function isDefaultSettings(?string $offset): bool
    {
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
