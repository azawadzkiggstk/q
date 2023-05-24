<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Type\Interface;

use Statik\GraphQL\GraphQL\Type\Field\GutenbergBlockAttributesField;
use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class GutenbergBlockInterface.
 */
class GutenbergBlockInterface implements Hookable
{
    public const INTERFACE_NAME = 'GutenbergBlock';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerType']);
    }

    /**
     * Register Gutenberg Block type interface.
     *
     * @throws \Exception
     */
    public function registerType(): void
    {
        \register_graphql_interface_type(
            self::INTERFACE_NAME,
            [
                'description' => \__(
                    'The single Statik Gutenberg block that are generated in Gutenberg editor.',
                    'statik-graphql'
                ),
                'interfaces' => ['Node'],
                'fields' => [
                    'id' => [
                        'type' => ['non_null' => 'ID'],
                        'description' => \__('The ID of single Gutenberg block.', 'statik-graphql'),
                    ],
                    'attributes' => [
                        'type' => ['list_of' => GutenbergBlockAttributesField::FIELD_NAME],
                        'description' => \__('The attributes assigned for single Gutenberg block.', 'statik-graphql'),
                    ],
                    'databaseId' => [
                        'type' => ['non_null' => 'Int'],
                        'description' => \__('The database ID of single Gutenberg block.', 'statik-graphql'),
                    ],
                    'parentId' => [
                        'type' => 'ID',
                        'description' => \__('The ID of parent Gutenberg block.', 'statik-graphql'),
                    ],
                    'parentDatabaseId' => [
                        'type' => 'Int',
                        'description' => \__('The database ID of parent Gutenberg block.', 'statik-graphql'),
                    ],
                    'postId' => [
                        'type' => ['non_null' => 'ID'],
                        'description' => \__('The ID of parent Content Node post.', 'statik-graphql'),
                    ],
                    'postDatabaseId' => [
                        'type' => ['non_null' => 'Int'],
                        'description' => \__('The database ID of parent Content Node post.', 'statik-graphql'),
                    ],
                    'name' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The name of single Gutenberg block.', 'statik-graphql'),
                    ],
                    'block' => [
                        'type' => 'String',
                        'description' => \__('The block type configuration as a JSON.', 'statik-graphql'),
                    ],
                    'rawHtml' => [
                        'type' => 'String',
                        'description' => \__('The raw HTML of single Gutenberg block.', 'statik-graphql'),
                    ],
                ],
                'resolveType' => static function ($value): string {
                    $nameSlug = \str_replace(['/', '-'], '', \ucwords($value->name, '/-'));

                    /**
                     * Fire block type resolver.
                     *
                     * @param string $type  resolver name
                     * @param object $value block data
                     *
                     * @return string
                     */
                    return (string) \apply_filters('Statik\GraphQL\blockTypeResolver', "{$nameSlug}Block", $value);
                },
            ]
        );
    }
}
