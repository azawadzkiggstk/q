<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Field;

use GraphQL\Deferred;
use Statik\GraphQL\GraphQL\Data\Loader\GutenbergBlockTypesLoader;
use Statik\GraphQL\GraphQL\Type\Field\GutenbergBlockTypeField as GutenbergBlockTypeFieldType;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use Statik\GraphQL\GraphQL\Utils\Relay;
use WPGraphQL\AppContext;

/**
 * Class GutenbergBlockTypeField.
 */
class GutenbergBlockTypeField implements Hookable
{
    public const FIELD_NAME = 'gutenbergBlockType';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'register_field']);
    }

    /**
     * Register global Gutenberg Block field.
     *
     * @throws \Exception
     */
    public function register_field(): void
    {
        \register_graphql_field(
            'RootQuery',
            self::FIELD_NAME,
            [
                'type' => GutenbergBlockTypeFieldType::FIELD_NAME,
                'description' => \__('The single Gutenberg block type.', 'statik-graphql'),
                'args' => [
                    'name' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The name of the object.', 'statik-graphql'),
                    ],
                ],
                'resolve' => static function ($source, array $args, AppContext $context): ?Deferred {
                    return $context
                        ->get_loader(GutenbergBlockTypesLoader::LOADER_NAME)
                        ->load_deferred(Relay::toGlobalId('block-type', $args['name']));
                },
            ]
        );
    }
}
