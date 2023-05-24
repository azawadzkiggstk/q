<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Type\Enum;

use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class ContentTypeDateFormatEnum.
 */
class ContentTypeDateFormatEnum implements Hookable
{
    public const TYPE_NAME = 'ContentTypeDateFormatEnum';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerType']);
    }

    /**
     * Register Content Type Date Format type.
     */
    public function registerType(): void
    {
        \register_graphql_enum_type(
            self::TYPE_NAME,
            [
                'description' => \__('Preformatted string of post publishing date.', 'statik-graphql'),
                'values' => [
                    'DATE' => [
                        'value' => 'date',
                        'description' => \__(
                            'Display Post publishing date formatted by value in Global Settings.',
                            'statik-graphql'
                        ),
                    ],
                    'TIME' => [
                        'value' => 'time',
                        'description' => \__(
                            'Display Post publishing time formatted by value in Global Settings.',
                            'statik-graphql'
                        ),
                    ],
                    'FROM_NOW' => [
                        'value' => 'from_now',
                        'description' => \__('Formatted human readable string of post publishing date.', 'statik-graphql'),
                    ],
                    'CUSTOM' => [
                        'value' => 'custom',
                        'description' => \__(
                            \sprintf(
                                'Formatted string of post publishing date. See %s for documentation for different formats.',
                                'https://www.php.net/manual/en/datetime.format.php#refsect1-datetime.format-parameters'
                            ),
                            'statik-graphql'
                        ),
                    ],
                ],
            ]
        );
    }
}
