<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_action('init', 'statik_register_video_categories_taxonomy', 1);

if (false === \function_exists('statik_register_video_categories_taxonomy')) {
    /**
     * Register categories taxonomy for Video.
     */
    function statik_register_video_categories_taxonomy(): void
    {
        $labels = [
            'name' => \__('Categories', 'statik-luna'),
            'singular_name' => \__('Category', 'statik-luna'),
            'menu_name' => \__('Categories', 'statik-luna'),
            'all_items' => \__('All Categories', 'statik-luna'),
            'parent_item' => \__('Parent Category', 'statik-luna'),
            'parent_item_colon' => \__('Parent Category:', 'statik-luna'),
            'new_item_name' => \__('New Category Name', 'statik-luna'),
            'add_new_item' => \__('Add New Category', 'statik-luna'),
            'edit_item' => \__('Edit Category', 'statik-luna'),
            'update_item' => \__('Update Category', 'statik-luna'),
            'view_item' => \__('View Category', 'statik-luna'),
            'separate_items_with_commas' => \__('Separate Categories with commas', 'statik-luna'),
            'add_or_remove_items' => \__('Add or remove Categories', 'statik-luna'),
            'choose_from_most_used' => \__('Choose from the most used', 'statik-luna'),
            'popular_items' => \__('Popular Categories', 'statik-luna'),
            'search_items' => \__('Search Categories', 'statik-luna'),
            'not_found' => \__('Not Found', 'statik-luna'),
            'no_terms' => \__('No Categories', 'statik-luna'),
            'items_list' => \__('Categories list', 'statik-luna'),
            'items_list_navigation' => \__('Categories list navigation', 'statik-luna'),
        ];

        \register_taxonomy(
            'videos_category',
            ['video'],
            [
                'labels' => $labels,
                'hierarchical' => true,
                'public' => false,
                'show_ui' => true,
                'show_admin_column' => true,
                'show_in_nav_menus' => true,
                'show_in_graphql' => true,
                'show_in_rest' => true,
                'show_in_rest' => true,
                'graphql_single_name' => 'videosCategory',
                'graphql_plural_name' => 'videosCategories',
            ]
        );
    }
}
