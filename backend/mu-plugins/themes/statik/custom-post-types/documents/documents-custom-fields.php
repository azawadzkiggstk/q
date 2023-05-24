<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_action('init', 'statik_register_documents_custom_fields', 1);

if (false === \function_exists('statik_register_documents_custom_fields')) {
    /**
     * Initialise and add fields to ACF settings.
     */
    function statik_register_documents_custom_fields(): void
    {
        if (false === \function_exists('acf_add_local_field_group')) {
            return;
        }

        \acf_add_local_field_group([
            'key' => 'group_5ee8dd0fda19d',
            'title' => \__('Document', 'statik-luna'),
            'fields' => [
                [
                    'key' => 'field_5efe356ag967b',
                    'label' => \__('Description', 'statik-luna'),
                    'name' => 'description',
                    'type' => 'wysiwyg',
                    'instructions' => \__('Description can be presented next to file.', 'statik-luna'),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'default_value' => '',
                    'tabs' => 'all',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'delay' => 1,
                ],
                [
                    'key' => 'field_5ee8dd16f34bd',
                    'label' => \__('Document file', 'statik-luna'),
                    'name' => 'media',
                    'type' => 'file',
                    'instructions' => \__('Select a document from the media library.', 'statik-luna'),
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'return_format' => 'array',
                    'library' => 'all',
                    'min_size' => 0,
                    'max_size' => 0,
                    'mime_types' => '',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'document',
                    ],
                ],
            ],
            'menu_order' => 1,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'left',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_graphql' => true,
            'graphql_field_name' => 'acf',
            'graphql_types' => ['Document'],
        ]);
    }
}
