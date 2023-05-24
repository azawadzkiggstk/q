<?php

declare(strict_types=1);
/**
 * Server-side rendering of the `core/image` block.
 *
 * @param mixed $attributes
 * @param mixed $content
 */

/**
 * Renders the `core/image` block on the server,
 * adding a data-id attribute to the element if core/gallery has added on pre-render.
 *
 * @param array  $attributes the block attributes
 * @param string $content    the block content
 *
 * @return string returns the block content with the data-id attribute added
 */
function render_block_core_image($attributes, $content)
{
    if (isset($attributes['data-id'])) {
        // Add the data-id="$id" attribute to the img element
        // to provide backwards compatibility for the Gallery Block,
        // which now wraps Image Blocks within innerBlocks.
        // The data-id attribute is added in a core/gallery `render_block_data` hook.
        $data_id_attribute = 'data-id="' . \esc_attr($attributes['data-id']) . '"';
        if (!\str_contains($content, $data_id_attribute)) {
            $content = \str_replace('<img', '<img ' . $data_id_attribute . ' ', $content);
        }
    }

    return $content;
}

/**
 * Registers the `core/image` block on server.
 */
function register_block_core_image(): void
{
    \register_block_type_from_metadata(
        __DIR__ . '/image',
        [
            'render_callback' => 'render_block_core_image',
        ]
    );
}
\add_action('init', 'register_block_core_image');
