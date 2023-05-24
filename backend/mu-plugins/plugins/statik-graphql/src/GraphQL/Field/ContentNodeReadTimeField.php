<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Field;

use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\Model\Post;

/**
 * Class ContentNodeReadTimeField.
 */
class ContentNodeReadTimeField implements Hookable
{
    public const FIELD_NAME = 'readTime';

    private int $wordsPerMinute;

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'register_field']);

        /**
         * Fire words per minute filter.
         *
         * @param int $wordsPerMinute words per minute
         *
         * @return int
         */
        $this->wordsPerMinute = \max((int) \apply_filters('Statik\GraphQl\wordsPerMinute', 160), 1);
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
                'type' => 'Number',
                'description' => \__('Read time in minutes.', 'statik-graphql'),
                'resolve' => function (Post $source): int {
                    $wordsCount = (int) \str_word_count(\strip_tags((string) $source->contentRendered));

                    return (int) \ceil($wordsCount / $this->wordsPerMinute);
                },
            ]
        );
    }
}
