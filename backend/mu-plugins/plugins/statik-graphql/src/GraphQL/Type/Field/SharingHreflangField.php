<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Type\Field;

use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class SharingHreflangField.
 */
class SharingHreflangField implements Hookable
{
    public const FIELD_NAME = 'SharingHreflangField';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerType']);
    }

    /**
     * Register object type for Statik Gutenberg Block.
     */
    public function registerType(): void
    {
        \register_graphql_object_type(
            static::FIELD_NAME,
            [
                'description' => \__('The Sharing Hreflang settings.', 'statik-graphql'),
                'fields' => [
                    'blogId' => [
                        'type' => ['non_null' => 'Number'],
                        'description' => \__('The ID of the hreflang blog.', 'statik-graphql'),
                    ],
                    'isBlogHidden' => [
                        'type' => ['non_null' => 'Boolean'],
                        'description' => \__('Whether the blog is hidden, i.e. deleted.', 'statik-graphql'),
                    ],
                    'postId' => [
                        'type' => ['non_null' => 'Number'],
                        'description' => \__('The ID of the hreflang post.', 'statik-graphql'),
                    ],
                    'permalink' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The URL of the hreflang post.', 'statik-graphql'),
                    ],
                    'language' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The language of the hreflang.', 'statik-graphql'),
                    ],
                    'isDefault' => [
                        'type' => ['non_null' => 'Boolean'],
                        'description' => \__('Whether the blog is a default one.', 'statik-graphql'),
                    ],
                ],
            ]
        );
    }
}
