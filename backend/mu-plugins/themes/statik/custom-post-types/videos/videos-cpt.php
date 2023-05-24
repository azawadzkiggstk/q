<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_action('init', 'statik_register_videos_cpt', 1);

if (false === \function_exists('statik_register_videos_cpt')) {
    /**
     * Register custom post type for Videos.
     */
    function statik_register_videos_cpt(): void
    {
        $labels = [
            'name' => \__('Videos', 'statik-luna'),
            'singular_name' => \__('Video', 'statik-luna'),
            'menu_name' => \__('Videos', 'statik-luna'),
            'name_admin_bar' => \__('Videos', 'statik-luna'),
            'archives' => \__('Videos Archives', 'statik-luna'),
            'attributes' => \__('Video Attributes', 'statik-luna'),
            'parent_item_colon' => \__('Parent Video:', 'statik-luna'),
            'all_items' => \__('All Videos', 'statik-luna'),
            'add_new_item' => \__('Add New Video', 'statik-luna'),
            'add_new' => \__('Add New', 'statik-luna'),
            'new_item' => \__('New Video', 'statik-luna'),
            'edit_item' => \__('Edit Video', 'statik-luna'),
            'update_item' => \__('Update Video', 'statik-luna'),
            'view_item' => \__('View Video', 'statik-luna'),
            'view_items' => \__('View Videos', 'statik-luna'),
            'search_items' => \__('Search Video', 'statik-luna'),
            'not_found' => \__('Not found', 'statik-luna'),
            'not_found_in_trash' => \__('Not found in Trash', 'statik-luna'),
            'featured_image' => \__('Featured Image', 'statik-luna'),
            'set_featured_image' => \__('Set featured image', 'statik-luna'),
            'remove_featured_image' => \__('Remove featured image', 'statik-luna'),
            'use_featured_image' => \__('Use as featured image', 'statik-luna'),
            'insert_into_item' => \__('Insert into Video', 'statik-luna'),
            'uploaded_to_this_item' => \__('Uploaded to this Video', 'statik-luna'),
            'items_list' => \__('Videos list', 'statik-luna'),
            'items_list_navigation' => \__('Videos list navigation', 'statik-luna'),
            'filter_items_list' => \__('Filter Videos list', 'statik-luna'),
        ];

        \register_post_type(
            'video',
            [
                'label' => \__('Video', 'statik-luna'),
                'description' => \__('Internal videos library', 'statik-luna'),
                'labels' => $labels,
                'supports' => ['title', 'excerpt', 'custom-fields', 'thumbnail', 'revisions'],
                'taxonomies' => ['videos_tag', 'videos_category'],
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 20,
                'menu_icon' => 'dashicons-video-alt2',
                'show_in_admin_bar' => true,
                'show_in_nav_menus' => true,
                'can_export' => true,
                'has_archive' => false,
                'exclude_from_search' => true,
                'publicly_queryable' => true,
                'capability_type' => 'post',
                'show_in_rest' => true,
                'rest_base' => 'videos',
                'rewrite' => ['with_front' => false],
                'show_in_graphql' => true,
                'graphql_single_name' => 'video',
                'graphql_plural_name' => 'videos',
            ]
        );
    }
}
