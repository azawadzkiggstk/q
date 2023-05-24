<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Field;

use Statik\GraphQL\GraphQL\Type\Enum\ContentTypeDateFormatEnum;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\Model\Post;
use WPGraphQL\Utils\Utils;

/**
 * Class ContentNodeDateFormatField.
 */
class ContentNodeDateFormatField implements Hookable
{
    public const FIELD_NAME = 'dateFormat';

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
            'ContentNode',
            self::FIELD_NAME,
            [
                'type' => 'String',
                'description' => \__('Formatted post publishing date.', 'statik-graphql'),
                'args' => [
                    'format' => [
                        'type' => ContentTypeDateFormatEnum::TYPE_NAME,
                        'description' => \__('Preformatted string of post publishing date.', 'statik-graphql'),
                    ],
                    'customFormat' => [
                        'type' => 'String',
                        'description' => \__(
                            \sprintf(
                                'Formatted string of post publishing date. See %s for documentation for different formats.',
                                'https://www.php.net/manual/en/datetime.format.php#refsect1-datetime.format-parameters'
                            ),
                            'statik-graphql'
                        ),
                    ],
                ],
                'resolve' => static function (Post $source, array $args): ?string {
                    $post = \get_post($source->databaseId);

                    if (empty($post->post_date) || '0000-00-00 00:00:00' === $post->post_date) {
                        return null;
                    }

                    $defaultDate = Utils::prepare_date_response($post->post_date_gmt, $post->post_date);

                    if (null === $defaultDate) {
                        return null;
                    }

                    if ($args['customFormat'] ?? '') {
                        return \date($args['customFormat'], \strtotime($defaultDate));
                    }

                    return match ($args['format'] ?? '') {
                        'time' => \date(\get_option('time_format'), \strtotime($defaultDate)),
                        'date' => \date(\get_option('date_format'), \strtotime($defaultDate)),
                        'from_now' => \human_time_diff(\time(), \strtotime($defaultDate)) . \__(' ago', 'statik-graphql'),
                        default => \date($args['customFormat'] ?? '', \strtotime($defaultDate)),
                    };
                },
            ]
        );
    }
}
