<?php

declare(strict_types=1);

\add_action('init', 'statik_initialize_acf_footer_options');

if (false === \function_exists('statik_initialize_acf_footer_options')) {
    /**
     * Initialise and add footer settings fields to ACF settings.
     */
    function statik_initialize_acf_footer_options(): void
    {
        if (false === \function_exists('acf_add_options_sub_page')) {
            return;
        }

        \acf_add_options_sub_page([
            'parent' => 'statik_theme_settings',
            'page_title' => \__('Footer settings', 'statik-luna'),
            'menu_title' => \__('Footer settings', 'statik-luna'),
            'menu_slug' => 'statik_footer_settings',
            'capability' => 'edit_posts',
            'redirect' => false,
            'show_in_graphql' => true,
            'graphql_field_name' => 'allStatikSettings',
            'position' => 60,
        ]);

        \acf_add_local_field_group([
            'key' => 'group_5ed57dd5d827a',
            'title' => 'Footer settings',
            'fields' => [
                [
                    'key' => 'field_5ed644402adcf',
                    'label' => __('Statik footer', 'statik-luna'),
                    'name' => 'statik_footer',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'message' => __('Display Statik footer next to copyright notice.', 'statik-luna'),
                    'default_value' => 1,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ],
                [
                    'key' => 'field_5ed57fb8193fc',
                    'label' => __('Footer paragraph', 'statik-luna'),
                    'name' => 'footer_paragraph',
                    'type' => 'textarea',
                    'instructions' => __('A text that is displayed in the footer section.', 'statik-luna'),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => '',
                ],
                [
                    'key' => 'field_5ed57aac3dc11',
                    'label' => __('Footer imprint', 'statik-luna'),
                    'name' => 'footer_imprint',
                    'type' => 'textarea',
                    'instructions' => __('A copyright text which is located in the lower section of a footer.',
                        'statik-luna'),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => '',
                ],
                [
                    'key' => 'field_5ed57dddd0a67',
                    'label' => __('Social media links', 'statik-luna'),
                    'name' => 'social-media',
                    'type' => 'group',
                    'instructions' => __(
                        'Links to a social media accounts. They are printed as icons in the footer section. Empty references won\'t be rendered in front-end application.',
                        'statik'
                    ),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'layout' => 'row',
                    'sub_fields' => [
                        [
                            'key' => 'field_5ed57e10d0a68',
                            'label' => __('Instagram', 'statik-luna'),
                            'name' => 'instagram',
                            'type' => 'url',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ],
                        [
                            'key' => 'field_5ed57e34d0a69',
                            'label' => __('LinkedIn', 'statik-luna'),
                            'name' => 'linkedin',
                            'type' => 'url',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ],
                        [
                            'key' => 'field_5ed5807df65fe',
                            'label' => __('Facebook', 'statik-luna'),
                            'name' => 'facebook',
                            'type' => 'url',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ],
                        [
                            'key' => 'field_5ed58085f65ff',
                            'label' => __('Twitter', 'statik-luna'),
                            'name' => 'twitter',
                            'type' => 'url',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'statik_footer_settings',
                    ],
                ],
            ],
            'menu_order' => 30,
            'position' => 'normal',
            'style' => '',
            'label_placement' => 'left',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_graphql' => true,
            'graphql_field_name' => 'footerSettings',
            'graphql_types' => ['AllStatikSettings'],
        ]);
    }
}
