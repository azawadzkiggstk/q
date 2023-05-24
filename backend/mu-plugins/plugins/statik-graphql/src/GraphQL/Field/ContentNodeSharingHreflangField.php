<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Field;

use Statik\GraphQL\GraphQL\Type\Field\SharingHreflangField;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use Statik\Sharing\Utils\Hreflang;
use WPGraphQL\Model\Post;

/**
 * Class ContentNodeSharingHreflangField.
 */
class ContentNodeSharingHreflangField implements Hookable
{
    public const FIELD_NAME = 'hreflang';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerConnection']);
    }

    /**
     * Register connections to Hreflang field.
     *
     * @throws \Exception
     */
    public function registerConnection(): void
    {
        \register_graphql_field(
            'ContentNode',
            self::FIELD_NAME,
            [
                'type' => ['list_of' => SharingHreflangField::FIELD_NAME],
                'description' => \__('Post hreflang structure.', 'statik-graphql'),
                'resolve' => static function (Post $post): array {
                    foreach (Hreflang::getHreflangStructure($post->ID) as $blog) {
                        $preparedBlogs[] = [
                            'blogId' => $blog['blogId'],
                            'isBlogHidden' => $blog['blogIsHidden'],
                            'postId' => $blog['postId'],
                            'permalink' => $blog['url'],
                            'language' => $blog['language'],
                            'isDefault' => $blog['default'],
                        ];
                    }

                    return $preparedBlogs ?? [];
                },
            ]
        );
    }
}
