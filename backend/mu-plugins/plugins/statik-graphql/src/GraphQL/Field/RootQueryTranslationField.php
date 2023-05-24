<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Field;

use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class RootQueryTranslationField.
 */
class RootQueryTranslationField implements Hookable
{
    public const FIELD_NAME = 'translation';

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
                'type' => 'String',
                'description' => \__('Retrieve the translation of text.', 'statik-graphql'),
                'args' => [
                    'text' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('Text to translate.', 'statik-graphql'),
                    ],
                    'domain' => [
                        'type' => 'String',
                        'description' => \__(
                            'Unique identifier for retrieving translated strings. Default "statik".',
                            'statik-graphql'
                        ),
                    ],
                    'context' => [
                        'type' => 'String',
                        'description' => \__('Context information for the translators.', 'statik-graphql'),
                    ],
                ],
                'resolve' => static function ($source, array $args): ?string {
                    if ($args['context'] ?? false) {
                        return \_x($args['text'], $args['context'], $args['domain'] ?: 'statik-graphql');
                    }

                    return \__($args['text'], $args['domain'] ?: 'statik-graphql');
                },
            ]
        );
    }
}
