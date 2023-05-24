<?php

declare(strict_types=1);

namespace Statik\Blocks\CustomBlocks\Core\Cover;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Statik\Blocks\Block\Block;
use Statik\Blocks\BlockType\AbstractBlockType;
use Statik\Blocks\BlockType\GraphQlConnectionsInterface;
use Statik\Blocks\BlockType\GraphQlExtraAttributesInterface;
use Statik\GraphQL\GraphQL\Model\GutenbergBlockModel;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\PostObjectConnectionResolver;

/**
 * Class Cover.
 */
class Cover extends AbstractBlockType implements GraphQlConnectionsInterface, GraphQlExtraAttributesInterface
{
    /**
     * {@inheritDoc}
     */
    public function getGraphQlExtraAttributes(): array
    {
        return [
            'alt' => static function (Block $block): ?string {
                $attribute = $block->block_type->attributes['alt'] ?? null;

                if (null === $attribute) {
                    return null;
                }

                $html = new \DOMDocument();
                $html->loadHTML($block->inner_html);
                $img = $html->getElementsByTagName($attribute['selector'])->item(0);

                foreach ($img->attributes ?? [] as $attr) {
                    /** @var \DOMAttr $attr */
                    if ($attr->nodeName === $attribute['attribute']) {
                        return $attr->nodeValue;
                    }
                }

                return $attribute['default'] ?? null;
            },
        ];
    }

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
