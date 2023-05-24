<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Type\Field;

use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class GutenbergBlockAttributesField.
 */
class GutenbergBlockAttributesField implements Hookable
{
    public const FIELD_NAME = 'GutenbergBlockAttributesField';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerType']);
    }

    /**
     * Register object type for Block with attributes.
     */
    public function registerType(): void
    {
        \register_graphql_object_type(
            self::FIELD_NAME,
            [
                'description' => \__('The single Gutenberg block attribute.', 'statik-graphql'),
                'fields' => [
                    'name' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The name of single Gutenberg block attribute.', 'statik-graphql'),
                    ],
                    'value' => [
                        'type' => 'String',
                        'description' => \__('The value of single Gutenberg block attribute.', 'statik-graphql'),
                    ],
                ],
            ]
        );
    }
}
