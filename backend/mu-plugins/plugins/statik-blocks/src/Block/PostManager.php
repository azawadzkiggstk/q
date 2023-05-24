<?php

declare(strict_types=1);

namespace Statik\Blocks\Block;

/**
 * Class PostManager.
 */
class PostManager
{
    private array $instances;

    /**
     * Parse post content to Gutenberg Blocks.
     */
    public function create(int|\WP_Post $post): Blocks
    {
        if (\is_int($post)) {
            $post = \get_post($post)->to_array();
        }

        if (false === isset($this->instances[$post['ID']])) {
            $this->instances[$post['ID']] = new Blocks(\parse_blocks($post['post_content']), ['post' => $post]);
        }

        return $this->instances[$post['ID']];
    }
}
