<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_action('init', 'statik_register_people_cpt', 1);

if (false === \function_exists('statik_register_people_cpt')) {
    /**
     * Register custom post type for People.
     */
    function statik_register_people_cpt(): void
    {
        $labels = [
            'name' => \__('People', 'statik-luna'),
            'singular_name' => \__('Person', 'statik-luna'),
            'menu_name' => \__('People', 'statik-luna'),
            'name_admin_bar' => \__('People', 'statik-luna'),
            'archives' => \__('People Archives', 'statik-luna'),
            'attributes' => \__('Person Attributes', 'statik-luna'),
            'parent_item_colon' => \__('Parent Person:', 'statik-luna'),
            'all_items' => \__('All People', 'statik-luna'),
            'add_new_item' => \__('Add New Person', 'statik-luna'),
            'add_new' => \__('Add New', 'statik-luna'),
            'new_item' => \__('New Person', 'statik-luna'),
            'edit_item' => \__('Edit Person', 'statik-luna'),
            'update_item' => \__('Update Person', 'statik-luna'),
            'view_item' => \__('View Person', 'statik-luna'),
            'view_items' => \__('View People', 'statik-luna'),
            'search_items' => \__('Search Person', 'statik-luna'),
            'not_found' => \__('Not found', 'statik-luna'),
            'not_found_in_trash' => \__('Not found in Trash', 'statik-luna'),
            'featured_image' => \__('Featured Image', 'statik-luna'),
            'set_featured_image' => \__('Set featured image', 'statik-luna'),
            'remove_featured_image' => \__('Remove featured image', 'statik-luna'),
            'use_featured_image' => \__('Use as featured image', 'statik-luna'),
            'insert_into_item' => \__('Insert into Person', 'statik-luna'),
            'uploaded_to_this_item' => \__('Uploaded to this Person', 'statik-luna'),
            'items_list' => \__('People list', 'statik-luna'),
            'items_list_navigation' => \__('People list navigation', 'statik-luna'),
            'filter_items_list' => \__('Filter People list', 'statik-luna'),
        ];

        \register_post_type(
            'person',
            [
                'label' => \__('Person', 'statik-luna'),
                'description' => \__('Characters of the company', 'statik-luna'),
                'labels' => $labels,
                'supports' => ['title', 'custom-fields', 'thumbnail', 'revisions', 'editor'],
                'taxonomies' => ['people_tag', 'people_category'],
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 20,
                'menu_icon' => 'dashicons-groups',
                'show_in_admin_bar' => true,
                'show_in_nav_menus' => true,
                'can_export' => true,
                'has_archive' => false,
                'exclude_from_search' => true,
                'publicly_queryable' => true,
                'capability_type' => 'post',
                'show_in_rest' => true,
                'rest_base' => 'people',
                'rewrite' => ['with_front' => false],
                'show_in_graphql' => true,
                'graphql_single_name' => 'person',
                'graphql_plural_name' => 'people',
            ]
        );
    }
}
