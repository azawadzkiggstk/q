<?php

declare(strict_types=1);

namespace Statik\Menu;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class DIProvider.
 */
class DIProvider implements ServiceProviderInterface
{
    public const CONTAINER_NAME = 'statik_menu_di';

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

        static::$isRegistered = true;
    }
}
