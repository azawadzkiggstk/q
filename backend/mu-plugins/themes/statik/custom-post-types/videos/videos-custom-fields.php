<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_action('init', 'statik_register_videos_custom_fields', 1);

if (false === \function_exists('statik_register_videos_custom_fields')) {
    /**
     * Initialise and add fields to ACF settings.
     */
    function statik_register_videos_custom_fields(): void
    {
        if (false === \function_exists('acf_add_local_field_group')) {
            return;
        }

        \acf_add_local_field_group([
            'key' => 'group_61e0178d888c9',
            'title' => \__('Video', 'statik-luna'),
            'fields' => [
                [
                    'key' => 'field_61e01794b5020',
                    'label' => \__('Video source', 'statik-luna'),
                    'name' => 'video_source',
                    'type' => 'select',
                    'instructions' => \__('Select the source of the video.', 'statik-luna'),
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'show_in_graphql' => 1,
                    'choices' => [
                        'local' => \__('Local file', 'statik-luna'),
                        'external' => \__('External provider', 'statik-luna'),
                    ],
                    'default_value' => 'local',
                    'allow_null' => 0,
                    'multiple' => 0,
                    'ui' => 0,
                    'return_format' => 'value',
                    'ajax' => 0,
                    'placeholder' => '',
                ],
                [
                    'key' => 'field_61e017ebb5021',
                    'label' => \__('Local file', 'statik-luna'),
                    'name' => 'local_file',
                    'type' => 'file',
                    'instructions' => \__('Select a video from the media library.', 'statik-luna'),
                    'required' => 1,
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_61e01794b5020',
                                'operator' => '==',
                                'value' => 'local',
                            ],
                        ],
                    ],
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'show_in_graphql' => 1,
                    'return_format' => 'id',
                    'library' => 'all',
                    'min_size' => '',
                    'max_size' => '',
                    'mime_types' => 'mp4',
                ],
                [
                    'key' => 'field_61e0187ab5022',
                    'label' => \__('Video URL', 'statik-luna'),
                    'name' => 'video_url',
                    'type' => 'url',
                    'instructions' => \__('Provide an URL of the External provider (youtube, vimeo) video.', 'statik-luna'),
                    'required' => 1,
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_61e01794b5020',
                                'operator' => '==',
                                'value' => 'external',
                            ],
                        ],
                    ],
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'show_in_graphql' => 1,
                    'default_value' => '',
                    'placeholder' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'video',
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
            'graphql_types' => ['Video'],
        ]);
    }
}
