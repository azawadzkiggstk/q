<?php

declare(strict_types=1);

\add_action('init', 'statik_initialize_acf_global_options');

if (false === \function_exists('statik_initialize_acf_global_options')) {
    /**
     * Initialise and add global settings fields to ACF settings.
     */
    function statik_initialize_acf_global_options(): void
    {
        if (false === \function_exists('acf_add_options_sub_page')) {
            return;
        }

        \acf_add_options_sub_page([
            'parent' => 'statik_theme_settings',
            'page_title' => \__('Global settings', 'statik-luna'),
            'menu_title' => \__('Global settings', 'statik-luna'),
            'menu_slug' => 'statik_global_settings',
            'capability' => 'edit_posts',
            'redirect' => false,
            'show_in_graphql' => true,
            'graphql_field_name' => 'allStatikSettings',
            'position' => 60,
        ]);

        \acf_add_local_field_group([
            'key' => 'group_5ed57dd4f8273',
            'title' => 'Global settings',
            'fields' => [
                [
                    'key' => 'field_60e2c9d426457',
                    'label' => __('Primary logo', 'statik-luna'),
                    'name' => 'primary_logo',
                    'type' => 'image',
                    'instructions' => __('A primary logo that is displayed on the top of a page.', 'statik-luna'),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ],
                [
                    'key' => 'field_60e2cac326458',
                    'label' => __('Secondary logo', 'statik-luna'),
                    'name' => 'secondary_logo',
                    'type' => 'image',
                    'instructions' => __(
                        'A secondary logo can be displayed in the footer or other place on a page.',
                        'statik'
                    ),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'statik_global_settings',
                    ],
                ],
            ],
            'menu_order' => 10,
            'position' => 'normal',
            'style' => '',
            'label_placement' => 'left',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_graphql' => true,
            'graphql_field_name' => 'globalSettings',
            'graphql_types' => ['AllStatikSettings'],
        ]);
    }
}
