<?php

declare(strict_types=1);

namespace Statik\Search\Search;

use Statik\GraphQL\GraphQL\Filters\ExecutionResponse;
use Statik\Search\DI;

/**
 * Class PostFilter.
 */
class PostFilter
{
    private array $allowedCpt;

    /**
     * PostFilter constructor.
     */
    public function __construct(private array $posts)
    {
        $this->allowedCpt = (array) DI::Config()->getValue('search.cpt', []);
    }

    /**
     * Prepare posts array for use in the Search client.
     */
    public function getPreparedPosts(): array
    {
        foreach ($this->posts as $postId) {
            $post = \get_post($postId);

            if (
                false === $post instanceof \WP_Post
                || 'publish' !== $post->post_status
                || false === \in_array($post->post_type, $this->allowedCpt)
            ) {
                $preparedPosts[] = $this->preparePostRemove($post);
            } else {
                $preparedPosts[] = $this->preparePostAdd($post);
            }
        }

        /**
         * Fire prepared posts filter.
         *
         * @param array $preparedPosts prepared posts
         *
         * @return array
         */
        return (array) \apply_filters('Statik\Search\preparedPosts', $preparedPosts ?? []);
    }

    /**
     * Prepare single post to add to Algolia.
     */
    public function preparePostAdd(\WP_Post $post): array
    {
        $preparedPost = [
            'postId' => $post->ID,
            'postType' => $post->post_type,
            'title' => $post->post_title,
            'content' => \wp_strip_all_tags($post->post_content, true),
            'excerpt' => $post->post_excerpt,
            'url' => \wp_make_link_relative(\get_the_permalink($post->ID)),
            'taxonomies' => [],
        ];

        foreach (\get_post_taxonomies($post->ID) as $taxonomy) {
            $preparedPost['taxonomies'] = \array_merge(
                $preparedPost['taxonomies'],
                \wp_get_post_terms($post->ID, $taxonomy, ['fields' => 'names'])
            );
        }

        if (\class_exists('\Statik\GraphQL\DI')) {
            /** @var ExecutionResponse $executor */
            $executor = \Statik\GraphQL\DI::GraphQL()->getFilter(ExecutionResponse::class);
            $data = $executor->filterExecuteResponse(['data' => $preparedPost]);
            $preparedPost = \is_array($data) ? $data['data'] : $data->data;
        }

        /**
         * Fire prepared post filter.
         *
         * @param array    $preparedPost prepared post
         * @param \WP_Post $post         original post
         *
         * @return array
         */
        $preparedPost = (array) \apply_filters('Statik\Search\preparedPost', $preparedPost, $post);

        return \array_merge($preparedPost, ['action' => 'updateObject']);
    }

    /**
     * Prepare single post to delete from Algolia.
     */
    public function preparePostRemove(\WP_Post $post): array
    {
        return ['action' => 'deleteObject', 'postId' => $post->ID, 'postType' => $post->post_type];
    }

    /**
     * Get all posts from WP that meets the conditions.
     */
    public static function getAllPosts(int $page = 1, int $perPage = -1): \WP_Query
    {
        /**
         * Fire search posts arguments filter.
         *
         * @param array $searchPostsArgs WP_Query arguments
         *
         * @return array
         */
        $searchPostsArgs = (array) \apply_filters('Statik\Search\searchPostsArgs', [
            'post_type' => (array) DI::Config()->getValue('search.cpt', []),
            'post_status' => 'publish',
            'fields' => 'ids',
            'posts_per_page' => $perPage,
            'paged' => $page,
        ]);

        return new \WP_Query($searchPostsArgs);
    }
}
