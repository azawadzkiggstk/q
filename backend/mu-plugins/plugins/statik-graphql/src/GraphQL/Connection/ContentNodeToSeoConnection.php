<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Connection;

use GraphQL\Deferred;
use Statik\GraphQL\GraphQL\Type\Field\NodeSeoField;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use Statik\GraphQL\GraphQL\Utils\Relay;
use Statik\GraphQL\GraphQL\Utils\Text;
use WPGraphQL\Model\Post;

/**
 * Class ContentNodeToSeoConnection.
 */
class ContentNodeToSeoConnection implements Hookable
{
    public const CONNECTION_FIELD_NAME = 'seo';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerConnection']);
    }

    /**
     * Register connections to Gutenberg Blocks.
     *
     * @throws \Exception
     */
    public function registerConnection(): void
    {
        $allowedPostTypes = \WPGraphQL::get_allowed_post_types();
        $excludedSeoPostTypes = \the_seo_framework()->get_option('disabled_post_types');

        foreach ($allowedPostTypes as $postType) {
            if (\array_key_exists($postType, $excludedSeoPostTypes)) {
                continue;
            }

            $postTypeObject = \get_post_type_object($postType);

            // Register SEO settings for each CPT
            \register_graphql_connection([
                'fromType' => $postTypeObject->graphql_single_name ?? null,
                'fromFieldName' => self::CONNECTION_FIELD_NAME,
                'toType' => NodeSeoField::FIELD_NAME,
                'oneToOne' => true,
                'resolve' => fn (Post $post): Deferred => $this->getSeoNode($post),
            ]);
        }
    }

    /**
     * Get SEO node.
     */
    private function getSeoNode(Post $post): Deferred
    {
        $tsf = \the_seo_framework();
        $postData = ['id' => $post->databaseId];
        $robotsDetails = $tsf->generate_robots_meta($postData);

        return new Deferred(fn (): array => [
            'node' => [
                'id' => Relay::toGlobalId(self::CONNECTION_FIELD_NAME, $post->databaseId),
                'title' => static fn (): string => Text::toUtf8($tsf->get_title($postData)),
                'description' => static fn (): string => Text::toUtf8($tsf->get_description($postData)),
                'ogTitle' => static fn (): string => Text::toUtf8($tsf->get_open_graph_title($postData)),
                'ogDescription' => static fn (): string => Text::toUtf8($tsf->get_open_graph_description($postData)),
                'twitterTitle' => static fn (): string => Text::toUtf8($tsf->get_twitter_title($postData)),
                'twitterDescription' => static fn (): string => Text::toUtf8($tsf->get_twitter_description($postData)),
                'imageUrl' => static fn () => $tsf->get_image_details($postData)[0]['url'] ?? null,
                'redirectUrl' => static fn (): ?string => $tsf->get_redirect_url($postData) ?: null,
                'canonicalUrl' => fn (): string => $this->getCanonicalUrl($post, $tsf),
                'noindex' => $robotsDetails['noindex'] ?? '' === 'noindex',
                'nofollow' => $robotsDetails['nofollow'] ?? '' === 'nofollow',
                'noarchive' => $robotsDetails['noarchive'] ?? '' === 'noarchive',
            ],
        ]);
    }

    /**
     * Get Canonical URL.
     */
    private function getCanonicalUrl(Post $post, object $tsf): string
    {
        return $tsf->get_canonical_url(['id' => $post->databaseId, 'get_custom_field' => true])
            ?: $tsf->get_canonical_url(['id' => $post->databaseId]);
    }
}
