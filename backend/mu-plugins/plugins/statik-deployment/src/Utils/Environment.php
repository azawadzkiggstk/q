<?php

declare(strict_types=1);

namespace Statik\Deploy\Utils;

use Statik\Deploy\DI;

/**
 * Class Environment.
 */
class Environment
{
    /**
     * Get current environment name.
     */
    public static function getEnvironmentName(bool $useDefault = true): ?string
    {
        $environments = DI::Config()->get('env', []);
        $envParam = \filter_input(\INPUT_GET, 'env');

        return \array_key_exists($envParam, $environments)
            ? $envParam
            : ($environments && $useDefault ? (string) \key($environments) : null);
    }
}
