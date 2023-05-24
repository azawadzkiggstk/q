<?php

declare(strict_types=1);

if (false === \function_exists('the_seo_framework')) {
    return;
}

\add_filter('Statik\Search\searchPostsArgs', 'statik_seo_exclude_search');

if (false === \function_exists('statik_seo_exclude_search')) {
    /**
     * Exclude from search posts where Search exclude in SEO is enabled.
     */
    function statik_seo_exclude_search(array $args): array
    {
        $extraArgs = ['key' => 'exclude_local_search', 'compare' => 'NOT EXISTS'];

        if ($args['meta_query']['relation'] ?? 'AND' === 'OR') {
            $args['meta_query'] = ['relation' => 'AND', $args['meta_query'], $extraArgs];
        } else {
            $args['meta_query'][] = $extraArgs;
        }

        return $args;
    }
}
