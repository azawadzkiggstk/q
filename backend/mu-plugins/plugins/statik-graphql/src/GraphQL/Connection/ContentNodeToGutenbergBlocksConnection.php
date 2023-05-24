<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Connection;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Statik\GraphQL\GraphQL\Data\Connection\GutenbergBlockConnectionResolver;
use Statik\GraphQL\GraphQL\Type\Interface\GutenbergBlockInterface;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\AppContext;
use WPGraphQL\Model\Post;

/**
 * Class ContentNodeToGutenbergBlocksConnection.
 */
class ContentNodeToGutenbergBlocksConnection implements Hookable
{
    public const CONNECTION_FIELD_NAME = 'gutenbergBlocks';

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
            'fromType' => 'ContentNode',
            'fromFieldName' => self::CONNECTION_FIELD_NAME,
            'toType' => GutenbergBlockInterface::INTERFACE_NAME,
            'resolve' => static function (
                Post $post,
                array $args,
                AppContext $context,
                ResolveInfo $info
            ): Deferred {
                $resolver = new GutenbergBlockConnectionResolver($post, $args, $context, $info);
                $resolver->set_query_arg('post_id', $post->ID);

                return $resolver->get_connection();
            },
        ]);
    }
}
