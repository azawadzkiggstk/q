<?php

declare(strict_types=1);

namespace Statik\Blocks\CustomBlocks\Statik\PostTypeTable;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Statik\Blocks\Block\Block;
use Statik\Blocks\BlockType\AbstractBlockType;
use Statik\Blocks\BlockType\EditorInlineDataInterface;
use Statik\Blocks\BlockType\GraphQlConnectionsInterface;
use Statik\Blocks\BlockType\GraphQlExtraAttributesInterface;
use Statik\Blocks\Utils\Acf\AcfFields;
use Statik\Blocks\Utils\PostType;
use Statik\GraphQL\GraphQL\Model\GutenbergBlockModel;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\PostObjectConnectionResolver;

/**
 * Class PostTypeTable.
 */
class PostTypeTable extends AbstractBlockType implements
    EditorInlineDataInterface,
    GraphQlExtraAttributesInterface,
    GraphQlConnectionsInterface
{
    /**
     * {@inheritDoc}
     */
    public function getEditorInlineData(): array
    {
        return [
            'acf' => static fn (): array => AcfFields::getFieldsNames(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getGraphQlExtraAttributes(): array
    {
        return [
            'posts' => static fn (Block $block): array => PostType::getPosts(
                $block->getAttributes('postType', 'post'),
                $block->getAttributes('query', [])
            ),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getGraphQLConnections(): array
    {
        return [
            'posts' => [
                'toType' => 'ContentNode',
                'resolve' => static function (
                    GutenbergBlockModel $block,
                    array $args,
                    AppContext $context,
                    ResolveInfo $info
                ): Deferred {
                    $posts = \json_decode($block->attributes['posts']['value']);
                    $resolver = new PostObjectConnectionResolver($block->postId, $args, $context, $info);
                    $resolver->set_query_arg('post__in', $posts ?: [\PHP_INT_MAX]);
                    $resolver->set_query_arg('orderby', 'post__in');

                    return $resolver->get_connection();
                },
            ],
        ];
    }
}
