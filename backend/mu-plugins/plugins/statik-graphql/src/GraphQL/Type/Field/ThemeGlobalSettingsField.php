<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Type\Field;

use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class ThemeGlobalSettingsField.
 */
class ThemeGlobalSettingsField implements Hookable
{
    public const FIELD_NAME = 'ThemeGlobalSettingsField';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerType']);
    }

    /**
     * Register object type for Theme with Global Settings.
     */
    public function registerType(): void
    {
        \register_graphql_object_type(
            static::FIELD_NAME,
            [
                'description' => \__('The theme Global Settings.', 'statik-graphql'),
                'interfaces' => ['Node'],
                'fields' => [
                    'raw' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The raw JSON of theme\'s Global Settings.', 'statik-graphql'),
                    ],
                    'border' => [
                        'type' => 'String',
                        'description' => \__('The border JSON of theme\'s Global Settings.', 'statik-graphql'),
                    ],
                    'color' => [
                        'type' => 'String',
                        'description' => \__('The color JSON of theme\'s Global Settings.', 'statik-graphql'),
                    ],
                    'spacing' => [
                        'type' => 'String',
                        'description' => \__('The spacing JSON of theme\'s Global Settings.', 'statik-graphql'),
                    ],
                    'typography' => [
                        'type' => 'String',
                        'description' => \__('The typography JSON of theme\'s Global Settings.', 'statik-graphql'),
                    ],
                    'blocks' => [
                        'type' => 'String',
                        'description' => \__('The blocks JSON of theme\'s Global Settings.', 'statik-graphql'),
                    ],
                    'custom' => [
                        'type' => 'String',
                        'description' => \__('The custom JSON of theme\'s Global Settings.', 'statik-graphql'),
                    ],
                    'layout' => [
                        'type' => 'String',
                        'description' => \__('The layout JSON of theme\'s Global Settings.', 'statik-graphql'),
                    ],
                ],
            ]
        );
    }
}
