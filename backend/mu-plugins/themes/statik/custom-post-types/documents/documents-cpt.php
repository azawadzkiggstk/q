<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_action('init', 'statik_register_documents_cpt', 1);

if (false === \function_exists('statik_register_documents_cpt')) {
    /**
     * Register custom post type for Documents.
     */
    function statik_register_documents_cpt(): void
    {
        $labels = [
            'name' => \__('Documents', 'statik-luna'),
            'singular_name' => \__('Document', 'statik-luna'),
            'menu_name' => \__('Documents', 'statik-luna'),
            'name_admin_bar' => \__('Documents', 'statik-luna'),
            'archives' => \__('Document Archives', 'statik-luna'),
            'attributes' => \__('Document Attributes', 'statik-luna'),
            'parent_item_colon' => \__('Parent Document:', 'statik-luna'),
            'all_items' => \__('All Documents', 'statik-luna'),
            'add_new_item' => \__('Add New Document', 'statik-luna'),
            'add_new' => \__('Add New', 'statik-luna'),
            'new_item' => \__('New Document', 'statik-luna'),
            'edit_item' => \__('Edit Document', 'statik-luna'),
            'update_item' => \__('Update Document', 'statik-luna'),
            'view_item' => \__('View Document', 'statik-luna'),
            'view_items' => \__('View Documents', 'statik-luna'),
            'search_items' => \__('Search Document', 'statik-luna'),
            'not_found' => \__('Not found', 'statik-luna'),
            'not_found_in_trash' => \__('Not found in Trash', 'statik-luna'),
            'featured_image' => \__('Featured Image', 'statik-luna'),
            'set_featured_image' => \__('Set featured image', 'statik-luna'),
            'remove_featured_image' => \__('Remove featured image', 'statik-luna'),
            'use_featured_image' => \__('Use as featured image', 'statik-luna'),
            'insert_into_item' => \__('Insert into Document', 'statik-luna'),
            'uploaded_to_this_item' => \__('Uploaded to this Document', 'statik-luna'),
            'items_list' => \__('Documents list', 'statik-luna'),
            'items_list_navigation' => \__('Documents list navigation', 'statik-luna'),
            'filter_items_list' => \__('Filter Documents list', 'statik-luna'),
        ];

        \register_post_type(
            'document',
            [
                'label' => \__('Document', 'statik-luna'),
                'description' => \__('Internal documents library', 'statik-luna'),
                'labels' => $labels,
                'supports' => ['title', 'custom-fields', 'revisions'],
                'taxonomies' => ['document_tag', 'document_category'],
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 20,
                'menu_icon' => 'dashicons-media-archive',
                'show_in_admin_bar' => true,
                'show_in_nav_menus' => true,
                'can_export' => true,
                'has_archive' => false,
                'exclude_from_search' => true,
                'publicly_queryable' => true,
                'capability_type' => 'post',
                'show_in_rest' => true,
                'rest_base' => 'documents',
                'rewrite' => ['with_front' => false],
                'show_in_graphql' => true,
                'graphql_single_name' => 'document',
                'graphql_plural_name' => 'documents',
            ]
        );
    }
}
