<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Connection;

use GraphQL\Deferred;
use Statik\GraphQL\GraphQL\Type\Field\NodeSeoField;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use Statik\GraphQL\GraphQL\Utils\Relay;
use Statik\GraphQL\GraphQL\Utils\Text;
use WPGraphQL\Model\Term;

/**
 * Class TermNodeToSeoConnection.
 */
class TermNodeToSeoConnection implements Hookable
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
        $allowedTaxonomies = \WPGraphQL::get_allowed_taxonomies();
        $excludedSeoTaxonomies = \the_seo_framework()->get_option('disabled_taxonomies');

        foreach ($allowedTaxonomies as $taxonomy) {
            if (\array_key_exists($taxonomy, $excludedSeoTaxonomies)) {
                continue;
            }

            $taxonomyObject = \get_taxonomy($taxonomy);

            // Register SEO settings for each Taxonomy
            \register_graphql_connection([
                'fromType' => $taxonomyObject->graphql_single_name ?? null,
                'fromFieldName' => self::CONNECTION_FIELD_NAME,
                'toType' => NodeSeoField::FIELD_NAME,
                'oneToOne' => true,
                'resolve' => fn (Term $term): Deferred => $this->getSeoNode($term),
            ]);
        }
    }

    /**
     * Get SEO node.
     */
    private function getSeoNode(Term $term): Deferred
    {
        $tsf = \the_seo_framework();
        $termData = ['id' => $term->term_id, 'taxonomy' => $term->taxonomyName];
        $robotsDetails = $tsf->generate_robots_meta($termData);

        return new Deferred(fn (): array => [
            'node' => [
                'id' => Relay::toGlobalId(self::CONNECTION_FIELD_NAME, $term->term_id),
                'title' => static fn (): string => Text::toUtf8($tsf->get_title($termData)),
                'description' => static fn (): string => Text::toUtf8($tsf->get_description($termData)),
                'ogTitle' => static fn (): string => Text::toUtf8($tsf->get_open_graph_title($termData)),
                'ogDescription' => static fn (): string => Text::toUtf8($tsf->get_open_graph_description($termData)),
                'twitterTitle' => static fn (): string => Text::toUtf8($tsf->get_twitter_title($termData)),
                'twitterDescription' => static fn (): string => Text::toUtf8($tsf->get_twitter_description($termData)),
                'imageUrl' => static fn () => $tsf->get_image_details($termData)[0]['url'] ?? null,
                'redirectUrl' => static fn (): ?string => $tsf->get_redirect_url($termData) ?: null,
                'canonicalUrl' => fn (): string => $this->getCanonicalUrl($term, $tsf),
                'noindex' => $robotsDetails['noindex'] ?? '' === 'noindex',
                'nofollow' => $robotsDetails['nofollow'] ?? '' === 'nofollow',
                'noarchive' => $robotsDetails['noarchive'] ?? '' === 'noarchive',
            ],
        ]);
    }

    /**
     * Get Canonical URL.
     */
    private function getCanonicalUrl(Term $term, object $tsf): string
    {
        $customFields = ['id' => $term->term_id, 'taxonomy' => $term->taxonomyName, 'get_custom_field' => true];

        return $tsf->get_canonical_url($customFields)
            ?: $tsf->get_canonical_url(['id' => $term->term_id, 'taxonomy' => $term->taxonomyName]);
    }
}
