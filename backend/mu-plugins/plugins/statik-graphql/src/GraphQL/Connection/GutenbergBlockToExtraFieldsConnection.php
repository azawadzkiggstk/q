<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Connection;

use Statik\Blocks\BlockType\GraphQLConnectionsInterface;
use Statik\Blocks\DI;
use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class GutenbergBlockToExtraFieldsConnection.
 */
class GutenbergBlockToExtraFieldsConnection implements Hookable
{
    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerConnection']);
    }

    /**
     * Register connections to Gutenberg Blocks.
     *
     * @throws \Exception
     */
    public function registerConnection(): void
    {
        foreach (DI::BlocksManager()->getBlockTypesRegistry() as $name => $block) {
            if (false === ($block instanceof GraphQLConnectionsInterface)) {
                continue;
            }

            /** @var GraphQLConnectionsInterface $block */
            $nameSlug = \str_replace(['/', '-'], '', \ucwords($name, '/-'));

            foreach ($block->getGraphQLConnections() as $fieldName => $fieldConfig) {
                \register_graphql_connection([
                    'fromType' => "{$nameSlug}Block",
                    'fromFieldName' => $fieldName,
                    'toType' => $fieldConfig['toType'],
                    'oneToOne' => $fieldConfig['oneToOne'] ?? false,
                    'resolve' => $fieldConfig['resolve'] ?? null,
                ]);
            }
        }
    }
}
