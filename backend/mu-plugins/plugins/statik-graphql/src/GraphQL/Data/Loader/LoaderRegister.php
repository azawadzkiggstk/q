<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Data\Loader;

use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\AppContext;

/**
 * Class LoaderRegister.
 */
class LoaderRegister implements Hookable
{
    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_filter('graphql_data_loaders', [$this, 'registerLoaders'], 10, 2);
    }

    /**
     * Registers loaders to AppContext.
     */
    public function registerLoaders(array $loaders, AppContext $context): array
    {
        $loaders[GutenbergBlocksLoader::LOADER_NAME] = new GutenbergBlocksLoader($context);
        $loaders[GutenbergBlockTypesLoader::LOADER_NAME] = new GutenbergBlockTypesLoader($context);

        return $loaders;
    }
}
