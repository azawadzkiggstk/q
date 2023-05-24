<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Type\Field;

use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class NodeSeoField.
 */
class NodeSeoField implements Hookable
{
    public const FIELD_NAME = 'NodeSeoField';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerType']);
    }

    /**
     * Register object type for Node with SEO settings.
     */
    public function registerType(): void
    {
        \register_graphql_object_type(
            static::FIELD_NAME,
            [
                'description' => \__('The SEO Framework values for Content Node.', 'statik-graphql'),
                'interfaces' => ['Node'],
                'fields' => [
                    'title' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The SEO title for Content Node.', 'statik-graphql'),
                    ],
                    'description' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The SEO description for Content Node.', 'statik-graphql'),
                    ],
                    'ogTitle' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The SEO OpenGraph title for Content Node.', 'statik-graphql'),
                    ],
                    'ogDescription' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The SEO OpenGraph description for Content Node.', 'statik-graphql'),
                    ],
                    'twitterTitle' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The SEO Twitter title for Content Node.', 'statik-graphql'),
                    ],
                    'twitterDescription' => [
                        'type' => ['non_null' => 'String'],
                        'description' => \__('The SEO Twitter description for Content Node.', 'statik-graphql'),
                    ],
                    'imageUrl' => [
                        'type' => 'String',
                        'description' => \__('The SEO image URL for Content Node.', 'statik-graphql'),
                    ],
                    'redirectUrl' => [
                        'type' => 'String',
                        'description' => \__('The Redirect URL for Content Node.', 'statik-graphql'),
                    ],
                    'canonicalUrl' => [
                        'type' => 'String',
                        'description' => \__('The Canonical URL for Content Node.', 'statik-graphql'),
                    ],
                    'noindex' => [
                        'type' => ['non_null' => 'Boolean'],
                        'description' => \__('Determine if `noindex` meta is enabled for Content Node.', 'statik-graphql'),
                    ],
                    'nofollow' => [
                        'type' => ['non_null' => 'Boolean'],
                        'description' => \__('Determine if `nofollow` meta is enabled for Content Node.', 'statik-graphql'),
                    ],
                    'noarchive' => [
                        'type' => ['non_null' => 'Boolean'],
                        'description' => \__('Determine if `noarchive` meta is enabled for Content Node.', 'statik-graphql'),
                    ],
                ],
            ]
        );
    }
}
