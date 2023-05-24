<?php

declare(strict_types=1);

namespace Statik\Blocks;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Statik\Blocks\Block\PostManager;
use Statik\Blocks\Common\Config\ConfigInterface;
use Statik\Blocks\Common\Config\Driver\DatabaseDriver;
use Statik\Blocks\Common\Settings\Generator;
use Statik\Blocks\Common\Settings\GeneratorInterface;

/**
 * Class DIProvider.
 */
class DIProvider implements ServiceProviderInterface
{
    public const CONTAINER_NAME = 'statik_blocks_di';

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
            'statik_blocks_settings',
            new DatabaseDriver('statik_blocks_settings')
        );

        $pimple['generator'] = fn (): GeneratorInterface => new Generator($pimple['config'], 'statik_blocks');

        $pimple['blocksManager'] = fn (): BlocksManager => new BlocksManager();

        $pimple['postManager'] = fn (): PostManager => new PostManager();

        static::$isRegistered = true;
    }
}
