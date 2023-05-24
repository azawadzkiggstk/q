<?php

declare(strict_types=1);

namespace Statik\GraphQL;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Statik\GraphQL\Common\Config\ConfigInterface;
use Statik\GraphQL\Common\Config\Driver\DatabaseDriver;
use Statik\GraphQL\Common\Settings\Generator;
use Statik\GraphQL\Common\Settings\GeneratorInterface;
use Statik\GraphQL\GraphQL\GraphQL;

/**
 * Class DIProvider.
 */
class DIProvider implements ServiceProviderInterface
{
    public const CONTAINER_NAME = 'statik_graphql_di';

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
            'statik_graphql_settings',
            new DatabaseDriver('statik_graphql_settings')
        );

        $pimple['generator'] = fn (): GeneratorInterface => new Generator($pimple['config'], 'statik_graphql');

        $pimple['graphql'] = fn (): GraphQL => new GraphQL();

        static::$isRegistered = true;
    }
}
