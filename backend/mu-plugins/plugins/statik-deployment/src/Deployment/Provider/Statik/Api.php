<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Provider\Statik;

use GuzzleHttp\RequestOptions;
use Statik\Deploy\Deployment\Logger\PostLogger;
use Statik\Deploy\Deployment\Provider\Api\AbstractApi;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\Deployment\Provider\Error\ApiRequestException;
use Statik\Deploy\Deployment\Provider\Error\ApiResponseException;
use Statik\Deploy\Utils\AppPass;

/**
 * Class Api.
 */
class Api extends AbstractApi
{
    /**
     * {@inheritdoc}
     *
     * Wrapper for the `callApi` method that adds authorization headers
     * for all requests and change JSON to array.
     *
     * @throws ApiResponseException|ApiRequestException
     */
    protected function callApi(string $uri, string $method = 'GET', array $options = []): array
    {
        $apiUrl = \untrailingslashit($this->getSettings()->getValue('statik.api_url'));

        if (empty($apiUrl)) {
            throw new ApiResponseException(\__('Missing or invalid `API hook` value.', 'statik-deployment'), 400);
        }

        $apiKey = $this->getSettings()->getValue('statik.api_token');

        if (empty($apiKey)) {
            throw new ApiResponseException(\__('Missing or invalid `API token` value.', 'statik-deployment'), 400);
        }

        return parent::callApi(
            "{$apiUrl}{$uri}",
            $method,
            \array_merge($options, [RequestOptions::HEADERS => ['authorization' => \sprintf('Bearer %s', $apiKey)]])
        );
    }

    /**
     * {@inheritDoc}
     *
     * @throws ApiResponseException|ApiRequestException
     */
    public function create(
        string $name = null,
        bool $flushCache = false,
        bool $autoRelease = true,
        bool|int $preview = false,
        bool $useCredentials = false
    ): DeploymentInterface {
        $user = \wp_get_current_user();
        $meta = [
            'name' => $name,
            'mode' => $preview ? 'preview' : 'regular',
            'user' => [
                'id' => $user->ID ?? 0,
                'email' => $user->user_email ?? 'automatic@statik.space',
                'useCredentials' => $useCredentials,
            ],
            'source' => [
                'url' => \get_bloginfo('url'),
                /** This filter is documented in wp-includes/http.php */
                'agent' => \apply_filters(
                    'http_headers_useragent',
                    \sprintf('WordPress/%s; %s', \get_bloginfo('version'), \get_bloginfo('url')),
                    null
                ),
            ],
            'preview' => $preview
                ? [
                    'post' => $preview,
                    'path' => 'draft' === \get_post_status($preview)
                        ? "/preview-page-{$preview}/"
                        : \str_replace(\home_url(), '', \get_permalink($preview)),
                ]
                : null,
            'posts' => $preview
                ? [$preview]
                : PostLogger::getPostsDetails($this->getProvider()->getEnvironment(), true),
        ];

        try {
            $response = $this->callApi(
                '/',
                'POST',
                [
                    RequestOptions::JSON => \array_filter([
                        'prefix' => $this->getSettings()->getSitePrefix(),
                        'description' => $name,
                        'clean' => $flushCache,
                        'auto_release' => $autoRelease,
                        'meta' => \json_encode(\array_filter($meta, static fn (mixed $el): bool => null !== $el)),
                        'preview_id' => $preview ?: null,
                        'credentials' => $useCredentials ? AppPass::generateCredentials($user) : null,
                    ], static fn (mixed $el): bool => null !== $el),
                ]
            );
        } catch (ApiResponseException $e) {
            if ($preview > 0 && 409 === $e->getCode()) {
                $id = \str_replace('Deployment in progress, id: ', '', $e->getMessage());

                if ($id) {
                    return $this->get($id);
                }
            }

            throw $e;
        }

        return Deployment::instance($response, $this->getProvider());
    }

    /**
     * {@inheritDoc}
     *
     * @throws ApiResponseException|ApiRequestException
     */
    public function get(string $id): DeploymentInterface
    {
        $response = $this->callApi(
            "/{$id}/",
            'GET',
            [RequestOptions::FORM_PARAMS => ['prefix' => $this->getSettings()->getSitePrefix()]]
        );

        return Deployment::instance($response, $this->getProvider());
    }

    /**
     * {@inheritDoc}
     *
     * @throws ApiResponseException|ApiRequestException
     */
    public function fetch(int $perPage = 20, int $page = 1, bool|int $preview = false): array
    {
        $response = $this->callApi(
            '/',
            'GET',
            [
                RequestOptions::QUERY => [
                    'query' => $this->getSettings()->getSitePrefix(),
                    'query_strict' => 'true',
                    'order' => 'desc',
                    'page' => $page,
                    'limit' => $perPage,
                    'preview' => $preview ? 'true' : 'false',
                ],
            ]
        );

        return [
            'data' => \array_map(
                fn (array $raw) => Deployment::instance($raw, $this->getProvider()),
                $response['data'] ?? []
            ),
            'page' => $response['page'],
            'per_page' => $response['limit'],
            'total' => $response['total'],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @throws ApiResponseException|ApiRequestException
     */
    public function rollback(string $id): bool
    {
        $this->callApi(
            '/release/',
            'POST',
            [
                RequestOptions::HEADERS => ['Content-Type' => 'application/json'],
                RequestOptions::JSON => ['deployment_id' => $id, 'purge_cache' => true],
            ]
        );

        return true;
    }
}
