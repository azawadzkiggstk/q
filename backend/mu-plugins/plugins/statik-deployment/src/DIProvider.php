<?php

declare(strict_types=1);

namespace Statik\Deploy;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Statik\Deploy\Common\Config\ConfigInterface;
use Statik\Deploy\Common\Config\Driver\DatabaseDriver;
use Statik\Deploy\Common\Settings\Generator;
use Statik\Deploy\Common\Settings\GeneratorInterface;

/**
 * Class DIProvider.
 */
class DIProvider implements ServiceProviderInterface
{
    public const CONTAINER_NAME = 'statik_deployment_di';

    protected static bool $isRegistered = false;

    /**
     * Register global DI container.
     */
    public static function registerGlobalDI(): void
    {
        if (false === static::$isRegistered) {
            $GLOBALS[static::CONTAINER_NAME] = new Container();
            $GLOBALS[static::CONTAINER_NAME]->register(new self());
        }
    }

    /**
     * Register Classes in the DI container.
     */
    public function register(Container $pimple): void
    {
        if (static::$isRegistered) {
            return;
        }

        $pimple['config'] = fn (): ConfigInterface => Config::Instance(
            'statik_deployment_settings',
            new DatabaseDriver('statik_deployment_settings')
        );

        $pimple['generator'] = fn (): GeneratorInterface => new Generator(
            $pimple['config'],
            'statik_deployment',
            'statik-deployment/v1'
        );

        static::$isRegistered = true;
    }
}
