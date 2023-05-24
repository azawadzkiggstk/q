<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_action('init', 'statik_register_documents_tags_taxonomy', 1);

if (false === \function_exists('statik_register_documents_tags_taxonomy')) {
    /**
     * Register Tags taxonomy for Documents.
     */
    function statik_register_documents_tags_taxonomy(): void
    {
        $labels = [
            'name' => \__('Tags', 'statik-luna'),
            'singular_name' => \__('Tag', 'statik-luna'),
            'menu_name' => \__('Tags', 'statik-luna'),
            'all_items' => \__('All Tags', 'statik-luna'),
            'parent_item' => \__('Parent Tag', 'statik-luna'),
            'parent_item_colon' => \__('Parent Tag:', 'statik-luna'),
            'new_item_name' => \__('New Tag Name', 'statik-luna'),
            'add_new_item' => \__('Add New Tag', 'statik-luna'),
            'edit_item' => \__('Edit Tag', 'statik-luna'),
            'update_item' => \__('Update Tag', 'statik-luna'),
            'view_item' => \__('View Tag', 'statik-luna'),
            'separate_items_with_commas' => \__('Separate Tags with commas', 'statik-luna'),
            'add_or_remove_items' => \__('Add or remove Tags', 'statik-luna'),
            'choose_from_most_used' => \__('Choose from the most used', 'statik-luna'),
            'popular_items' => \__('Popular Tags', 'statik-luna'),
            'search_items' => \__('Search Tags', 'statik-luna'),
            'not_found' => \__('Not Found', 'statik-luna'),
            'no_terms' => \__('No Tags', 'statik-luna'),
            'items_list' => \__('Tags list', 'statik-luna'),
            'items_list_navigation' => \__('Tags list navigation', 'statik-luna'),
        ];

        \register_taxonomy(
            'documents_tag',
            ['document'],
            [
                'labels' => $labels,
                'hierarchical' => false,
                'public' => false,
                'show_ui' => true,
                'show_admin_column' => true,
                'show_in_nav_menus' => true,
                'show_in_rest' => true,
                'show_in_graphql' => true,
                'graphql_single_name' => 'documentsTag',
                'graphql_plural_name' => 'documentsTags',
            ]
        );
    }
}
