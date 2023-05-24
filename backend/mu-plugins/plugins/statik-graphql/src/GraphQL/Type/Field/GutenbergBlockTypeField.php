<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Type\Field;

use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class GutenbergBlockTypeField.
 */
class GutenbergBlockTypeField implements Hookable
{
    public const FIELD_NAME = 'GutenbergBlockTypeField';

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
                'description' => \__('The single Gutenberg block Type.', 'statik-graphql'),
                'interfaces' => ['Node'],
                'fields' => [
                    'databaseId' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The name of single Gutenberg block type.', 'statik-graphql'),
                    ],
                    'name' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The name of single Gutenberg block type.', 'statik-graphql'),
                    ],
                    'parent' => [
                        'type' => 'String',
                        'description' => \__('The parent block type name of single Gutenberg block type.', 'statik-graphql'),
                    ],
                    'title' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The title of single Gutenberg block type.', 'statik-graphql'),
                    ],
                    'description' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The description of single Gutenberg block type.', 'statik-graphql'),
                    ],
                    'supports' => [
                        'type' => 'String',
                        'description' => \__('The supports JSON of single Gutenberg block type.', 'statik-graphql'),
                    ],
                    'attributes' => [
                        'type' => 'String',
                        'description' => \__('The attributes JSON of single Gutenberg block type.', 'statik-graphql'),
                    ],
                    'usesContext' => [
                        'type' => 'String',
                        'description' => \__('The uses context JSON of single Gutenberg block type.', 'statik-graphql'),
                    ],
                    'apiVersion' => [
                        'type' => ['non_null' => 'Integer'],
                        'description' => \__('The uses context JSON of single Gutenberg block type.', 'statik-graphql'),
                    ],
                    'raw' => [
                        'type' => 'String',
                        'description' => \__('The raw JSON of single Gutenberg block type.', 'statik-graphql'),
                    ],
                ],
            ]
        );
    }
}
