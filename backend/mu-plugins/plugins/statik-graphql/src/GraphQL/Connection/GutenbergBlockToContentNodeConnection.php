<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Connection;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Statik\GraphQL\GraphQL\Model\GutenbergBlockModel;
use Statik\GraphQL\GraphQL\Type\Interface\GutenbergBlockInterface;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\PostObjectConnectionResolver;

/**
 * Class GutenbergBlockToContentNodeConnection.
 */
class GutenbergBlockToContentNodeConnection implements Hookable
{
    public const CONNECTION_FIELD_NAME = 'post';

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
        \register_graphql_connection([
            'fromType' => GutenbergBlockInterface::INTERFACE_NAME,
            'fromFieldName' => self::CONNECTION_FIELD_NAME,
            'toType' => 'ContentNode',
            'oneToOne' => true,
            'resolve' => static function (
                GutenbergBlockModel $block,
                array $args,
                AppContext $context,
                ResolveInfo $info
            ): Deferred {
                $resolver = new PostObjectConnectionResolver($block->postDatabaseId, $args, $context, $info);
                $resolver->set_query_arg('p', $block->postDatabaseId);

                return $resolver->one_to_one()->get_connection();
            },
        ]);
    }
}
