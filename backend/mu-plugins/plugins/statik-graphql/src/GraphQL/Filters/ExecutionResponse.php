<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Filters;

use GraphQL\Executor\ExecutionResult;
use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class ExecutionResponse.
 */
class ExecutionResponse implements Hookable
{
    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_filter('graphql_request_results', [$this, 'filterExecuteResponse']);
    }

    /**
     * Filter production assets domain in the response.
     */
    public function filterExecuteResponse(array|ExecutionResult $result): array|ExecutionResult
    {
        $tempUploadsUrl = 'https://temp-assets-j4hg8skj4u.com/';
        $responseIsArray = false === ($result instanceof ExecutionResult);
        $replaceAssets = \defined('STATIK_ASSETS_URL') && \filter_var(STATIK_ASSETS_URL, \FILTER_VALIDATE_URL);

        $resultData = \json_encode($responseIsArray ? $result['data'] : $result->data, \JSON_UNESCAPED_SLASHES);
        $uploadsUrl = \trailingslashit(\wp_upload_dir()['baseurl'] ?? WP_CONTENT_URL . '/uploads/');

        if ($replaceAssets) {
            $resultData = \str_replace($uploadsUrl, \trailingslashit(STATIK_ASSETS_URL), $resultData);
        }

        $resultData = \str_replace(
            [
                $uploadsUrl,
                \untrailingslashit(\home_url('', 'http')),
                \untrailingslashit(\home_url('', 'https')),
                $tempUploadsUrl,
            ],
            [
                $tempUploadsUrl,
                '',
                '',
                $uploadsUrl,
            ],
            $resultData
        );

        $resultData = \json_decode($resultData, true);

        if (\JSON_ERROR_NONE !== \json_last_error()) {
            return $result;
        }

        $responseIsArray ? ($result['data'] = $resultData) : ($result->data = $resultData);

        return $result;
    }
}
