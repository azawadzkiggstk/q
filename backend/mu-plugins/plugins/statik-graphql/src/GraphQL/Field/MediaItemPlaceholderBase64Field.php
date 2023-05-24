<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Field;

use GraphQL\Deferred;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use Statik\GraphQL\GraphQL\Utils\Media;
use WPGraphQL\Model\Post;

/**
 * Class MediaItemPlaceholderBase64Field.
 */
class MediaItemPlaceholderBase64Field implements Hookable
{
    public const FIELD_NAME = 'placeholderBase64';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'register_field']);
    }

    /**
     * Register Placeholder Base64 field in Media Item.
     *
     * @throws \Exception
     */
    public function register_field(): void
    {
        \register_graphql_field(
            'MediaItem',
            self::FIELD_NAME,
            [
                'type' => 'String',
                'description' => \__('The Placeholder image (20x20) encoded to Base64.', 'statik-graphql'),
                'resolve' => static fn (Post $post): Deferred => new Deferred(
                    static fn (): ?string => Media::getMediaBase64Placeholder($post->databaseId)
                ),
            ]
        );
    }
}
