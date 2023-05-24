<?php

declare(strict_types=1);

namespace Statik\Blocks\Utils;

/**
 * Class PostType.
 */
class PostType
{
    /**
     * Get posts to display.
     */
    public static function getPosts(string $postType, array $extraQuery = []): array
    {
        $postType = \get_post_type_object($postType);
        $restBase = $postType?->rest_base ?: $postType?->name ?: null;

        if (null === $restBase) {
            return [];
        }

        $request = new \WP_REST_Request('GET', "/wp/v2/{$restBase}");
        $request->set_query_params(\array_merge($extraQuery, ['_fields' => 'id']));
        $response = \rest_do_request($request);

        if ($response->status >= 400) {
            return [];
        }

        return \array_column(\rest_get_server()->response_to_data($response, false), 'id');
    }
}
