<?php

declare(strict_types=1);

namespace Statik\Blocks\CustomBlocks\Statik\Icon;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Statik\Blocks\BlockType\AbstractBlockType;
use Statik\Blocks\BlockType\GraphQlConnectionsInterface;
use Statik\GraphQL\GraphQL\Model\GutenbergBlockModel;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\PostObjectConnectionResolver;

/**
 * Class Icon.
 */
class Icon extends AbstractBlockType implements GraphQlConnectionsInterface
{
    /**
     * {@inheritDoc}
     */
    public function getGraphQLConnections(): array
    {
        return [
            'mediaItem' => [
                'toType' => 'MediaItem',
                'oneToOne' => true,
                'resolve' => static function (
                    GutenbergBlockModel $block,
                    array $args,
                    AppContext $context,
                    ResolveInfo $info
                ): ?Deferred {
                    if (null === $block->attributes['id']['value']) {
                        return null;
                    }

                    $resolver = new PostObjectConnectionResolver($block->databaseId, $args, $context, $info);
                    $resolver->set_query_arg('p', $block->attributes['id']['value']);
                    $resolver->set_query_arg('post_status', 'inherit');

                    return $resolver->one_to_one()->get_connection();
                },
            ],
        ];
    }
}
