<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * Hash the email value in the REST API using the ROT13 algorithm.
 */
\add_filter('acf/format_value/key=field_98cfaac809af0', 'str_rot13');

\add_filter(
    'graphql_acf_field_value',
    static fn ($value, $field) => 'field_98cfaac809af0' === $field['key'] ? \str_rot13($value) : $value,
    10,
    2
);

\add_action('init', 'statik_register_people_custom_fields', 1);

if (false === \function_exists('statik_register_people_custom_fields')) {
    /**
     * Initialise and add fields to ACF settings.
     */
    function statik_register_people_custom_fields(): void
    {
        if (false === \function_exists('acf_add_local_field_group')) {
            return;
        }

        \acf_add_local_field_group([
            'key' => 'group_5ede35d947155',
            'title' => \__('Person', 'statik-luna'),
            'fields' => [
                [
                    'key' => 'field_5ede35fae967b',
                    'label' => \__('Short description', 'statik-luna'),
                    'name' => 'short_description',
                    'type' => 'wysiwyg',
                    'instructions' => \__('Short description can be presented in the Person card.', 'statik-luna'),
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
                    'key' => 'field_5ede362be967c',
                    'label' => \__('Long description', 'statik-luna'),
                    'name' => 'long_description',
                    'type' => 'wysiwyg',
                    'instructions' => \__('Long description can be presented in the Person modal.', 'statik-luna'),
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
                    'rows' => 4,
                ],
                [
                    'key' => 'field_5ede49bdc8743',
                    'label' => \__('Contact details', 'statik-luna'),
                    'name' => 'contact_details',
                    'type' => 'group',
                    'instructions' => '',
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
                            'key' => 'field_5ehg49d7c8744',
                            'label' => \__('Website URL', 'statik-luna'),
                            'name' => 'website',
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
                            'key' => 'field_5ede49d7c8744',
                            'label' => \__('LinkedIn profile URL', 'statik-luna'),
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
                            'key' => 'field_5edk99d7c8744',
                            'label' => \__('Twitter profile URL', 'statik-luna'),
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
                        [
                            'key' => 'field_98cfaac809af0',
                            'label' => \__('E-mail address', 'statik-luna'),
                            'name' => 'email',
                            'type' => 'email',
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
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'person',
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
            'graphql_types' => ['Person'],
        ]);
    }
}
