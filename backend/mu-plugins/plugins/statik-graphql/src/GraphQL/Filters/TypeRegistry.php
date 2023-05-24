<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Filters;

use Statik\GraphQL\GraphQL\Registry\StatikTypeRegistry;
use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class TypeRegistry.
 */
class TypeRegistry implements Hookable
{
    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action(
            'graphql_type_registry',
            static fn (): StatikTypeRegistry => new StatikTypeRegistry()
        );
    }
}
