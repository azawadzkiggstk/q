<?php

declare(strict_types=1);

\add_action('init', 'statik_initialize_acf_cookie_options');

if (false === \function_exists('statik_initialize_acf_cookie_options')) {
    /**
     * Initialise and add cookie settings fields to ACF settings.
     */
    function statik_initialize_acf_cookie_options(): void
    {
        if (false === \function_exists('acf_add_options_sub_page')) {
            return;
        }

        \acf_add_options_sub_page([
            'parent' => 'statik_theme_settings',
            'page_title' => \__('Cookie consent settings', 'statik-luna'),
            'menu_title' => \__('Cookie settings', 'statik-luna'),
            'menu_slug' => 'statik_cookie_settings',
            'capability' => 'edit_posts',
            'redirect' => false,
            'show_in_graphql' => true,
            'graphql_field_name' => 'allStatikSettings',
            'position' => 60,
        ]);

        \acf_add_local_field_group([
            'key' => 'group_5ed57dd5d8273',
            'title' => __('Cookie consent settings', 'statik-luna'),
            'fields' => [
                [
                    'key' => 'field_5ed57fb8193f5',
                    'label' => __('Cookie consent message', 'statik-luna'),
                    'name' => 'cookie_message',
                    'type' => 'wysiwyg',
                    'instructions' => __('A text that is displayed in the cookie banner.', 'statik-luna'),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'default_value' => __(
                        'This site uses cookies to offer you a better browsing experience. Find out more on how we use cookies and how to disable them in the browser settings in Cookies Policy. By clicking I accept, you consent to the use of cookies unless you have disabled them in the browser settings.',
                        'statik'
                    ),
                ],
                [
                    'key' => 'field_5ed57aac3d511',
                    'label' => __('Cookie consent accept button text', 'statik-luna'),
                    'name' => 'accept_message',
                    'type' => 'text',
                    'instructions' => __(
                        'A text that is displayed in the accept button in the cookie banner.',
                        'statik'
                    ),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'default_value' => __('Accept', 'statik-luna'),
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => '',
                ],
                [
                    'key' => 'field_5ed57aa6sd511',
                    'label' => __('Cookie consent reject button text', 'statik-luna'),
                    'name' => 'reject_message',
                    'type' => 'text',
                    'instructions' => __(
                        'A text that is displayed in the reject button in the cookie banner.',
                        'statik'
                    ),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'default_value' => __('Reject', 'statik-luna'),
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => '',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'statik_cookie_settings',
                    ],
                ],
            ],
            'menu_order' => 20,
            'position' => 'normal',
            'style' => '',
            'label_placement' => 'left',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_graphql' => true,
            'graphql_field_name' => 'cookieConsentSettings',
            'graphql_types' => ['AllStatikSettings'],
        ]);
    }
}
