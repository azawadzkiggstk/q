<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Connection;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Statik\GraphQL\GraphQL\Data\Connection\GutenbergBlockTypeConnectionResolver;
use Statik\GraphQL\GraphQL\Type\Field\GutenbergBlockTypeField;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\AppContext;

/**
 * Class RootToGutenbergBlockTypesConnection.
 */
class RootToGutenbergBlockTypesConnection implements Hookable
{
    public const CONNECTION_FIELD_NAME = 'gutenbergBlockTypes';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerConnection']);
    }

    /**
     * Register connections to Gutenberg Block.
     *
     * @throws \Exception
     */
    public function registerConnection(): void
    {
        \register_graphql_connection([
            'fromType' => 'RootQuery',
            'toType' => GutenbergBlockTypeField::FIELD_NAME,
            'fromFieldName' => self::CONNECTION_FIELD_NAME,
            'resolve' => static function (
                $source,
                array $args,
                AppContext $context,
                ResolveInfo $info
            ): Deferred {
                $resolver = new GutenbergBlockTypeConnectionResolver($source, $args, $context, $info);

                return $resolver->get_connection();
            },
        ]);
    }
}
