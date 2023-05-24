<?php

declare(strict_types=1);

namespace Statik\Search\Rest\V1;

use Statik\Search\Common\Rest\ApiInterface;
use Statik\Search\Common\Rest\Controller\AbstractController;
use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\DI as DeployDI;
use Statik\Search\DI;
use Statik\Search\Search\Client\SearchClient;
use Statik\Search\Search\PostFilter;

/**
 * Class Deployment.
 *
 * The Deployment endpoint allows manage Deployments.
 */
class Search extends AbstractController
{
    private const POSTS_PER_PAGE = 50;

    /**
     * {@inheritdoc}
     */
    public function __construct(ApiInterface $api)
    {
        parent::__construct($api);

        $this->rest_base = 'search';
    }

    /**
     * {@inheritdoc}
     */
    public function registerRoutes(): void
    {
        \register_rest_route(
            $this->api->getNamespace(),
            "/{$this->rest_base}/(?P<environment>[\\w\\-\\.]++)",
            [
                'methods' => \WP_REST_Server::DELETABLE,
                'callback' => [$this, 'deleteEnvironmentSearch'],
                'permission_callback' => [$this, 'checkPermissions'],
                'args' => ['environment' => ['required' => true, 'type' => 'string']],
            ]
        );

        \register_rest_route(
            $this->api->getNamespace(),
            "/{$this->rest_base}/(?P<environment>[\\w\\-\\.]++)",
            [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [$this, 'updateEnvironmentSearch'],
                'permission_callback' => [$this, 'checkPermissions'],
                'args' => [
                    'environment' => ['required' => true, 'type' => 'string'],
                    'posts' => ['required' => false, 'type' => 'array', 'items' => ['type' => 'integer']],
                    'page' => ['required' => false, 'type' => 'integer', 'default' => 1],
                ],
            ]
        );

        \register_rest_route(
            $this->api->getNamespace(),
            "/{$this->rest_base}/(?P<environment>[\\w\\-\\.]++)/settings",
            [
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => [$this, 'updateEnvironmentSettings'],
                'permission_callback' => [$this, 'checkPermissions'],
                'args' => ['environment' => ['required' => true, 'type' => 'string']],
            ]
        );
    }

    /**
     * Delete environment search.
     */
    public function deleteEnvironmentSearch(\WP_REST_Request $request): \WP_Error|\WP_REST_Response
    {
        $environment = (string) $request->get_param('environment');

        if (false === DeployDI::Config()->get("env.{$environment}", false)) {
            return new \WP_Error(
                'rest_statik_invalid_environment',
                \__('Environment does not exists.', 'statik-search'),
                ['status' => 404]
            );
        }

        $deploymentManager = new DeploymentManager($environment);

        if ($deploymentManager->getLast(DeploymentInterface::IN_PROGRESS)) {
            return new \WP_Error(
                'rest_statik_deployment_in_progress',
                \__('There is an other deployment in progress. Check status and please try again.', 'statik-search'),
                ['status' => 500]
            );
        }

        try {
            $client = new SearchClient($environment);
            $response = $client->removeIndex();
        } catch (\Exception $e) {
            return new \WP_Error(
                'rest_statik_search_error',
                \json_decode($e->getMessage()) ?: $e->getMessage(),
                ['status' => $e->getCode()]
            );
        }

        /**
         * Fire when update triggered by API hook.
         *
         * @param array $updateResult deployment response from provider
         *
         * @return array
         */
        $updateResult = (array) \apply_filters(
            'Statik\Search\deleteSearchResponse',
            [
                'response' => [
                    'success' => (bool) ($response['deletedAt'] ?? false),
                    'raw' => DI::Config()->getValue('settings.debug') ? $response : null,
                    'stage' => 'SEARCH_UPDATING',
                ],
                'nextAction' => [
                    'path' => "{$this->api->getNamespace()}/{$this->rest_base}/{$environment}",
                    'method' => 'POST',
                    'delay' => 0,
                    'data' => ['posts' => [-1]],
                ],
            ]
        );

        return new \WP_REST_Response($updateResult);
    }

    /**
     * Update environment search based on provided data.
     */
    public function updateEnvironmentSearch(\WP_REST_Request $request): \WP_Error|\WP_REST_Response
    {
        $environment = (string) $request->get_param('environment');
        $page = (int) $request->get_param('page');
        $posts = (array) $request->get_param('posts');

        if (false === DeployDI::Config()->get("env.{$environment}", false)) {
            return new \WP_Error(
                'rest_statik_invalid_environment',
                \__('Environment does not exists.', 'statik-search'),
                ['status' => 404]
            );
        }

        $deploymentManager = new DeploymentManager($environment);

        if ($deploymentManager->getLast(DeploymentInterface::IN_PROGRESS)) {
            return new \WP_Error(
                'rest_statik_deployment_in_progress',
                \__('There is an other deployment in progress. Check status and please try again.', 'statik-search'),
                ['status' => 500]
            );
        }

        if (1 === \count($posts) && -1 === $posts[0]) {
            $query = PostFilter::getAllPosts($page, self::POSTS_PER_PAGE);
            $postsToInsert = $query->get_posts();
            $items = $query->found_posts;
            $maxPages = $query->max_num_pages;
            $nextPage = $maxPages <= $page ? null : $page + 1;
        } elseif (false === empty($posts)) {
            $postsToInsert = \array_slice($posts, ($page - 1) * self::POSTS_PER_PAGE, self::POSTS_PER_PAGE);
            $items = \count($posts);
            $maxPages = (int) \ceil($items / self::POSTS_PER_PAGE);
            $nextPage = $maxPages <= $page ? null : $page + 1;
        } else {
            /**
             * Fire when update triggered by API hook.
             *
             * @param array $updateResult deployment response from provider
             *
             * @return array
             */
            $updateResult = (array) \apply_filters(
                'Statik\Search\updateSearchResponse',
                [
                    'response' => [
                        'maxItems' => 0,
                        'maxPage' => null,
                        'success' => 0,
                        'status' => 0,
                        'readyStatus' => 'SEARCH_READY',
                    ],
                    'nextAction' => null,
                ]
            );

            return new \WP_REST_Response($updateResult);
        }

        try {
            $client = new SearchClient($environment);
            $response = $client->insertPostData($postsToInsert);
        } catch (\Exception $e) {
            return new \WP_Error(
                'rest_statik_search_error',
                \json_decode($e->getMessage()) ?: $e->getMessage(),
                ['status' => $e->getCode()]
            );
        }

        $nextAction = $nextPage
            ? [
                'path' => "{$this->api->getNamespace()}/{$this->rest_base}/{$environment}",
                'method' => 'POST',
                'delay' => 0,
                'data' => ['page' => $nextPage, 'posts' => $posts],
            ]
            : [
                'path' => "{$this->api->getNamespace()}/{$this->rest_base}/{$environment}/settings",
                'method' => 'POST',
                'delay' => 0,
                'data' => [],
            ];

        /**
         * Fire when update triggered by API hook.
         *
         * @param array $updateResult deployment response from provider
         *
         * @return array
         */
        $updateResult = (array) \apply_filters(
            'Statik\Search\updateSearchResponse',
            [
                'response' => [
                    'maxItems' => $items,
                    'maxPage' => $maxPages,
                    'success' => \min($items, $page * self::POSTS_PER_PAGE),
                    'status' => \count($response['objectIDs'] ?? []),
                    'raw' => DI::Config()->getValue('settings.debug') ? $response : null,
                    'stage' => $nextPage ? 'SEARCH_UPDATING' : 'SEARCH_SETTINGS',
                ],
                'nextAction' => $nextAction,
            ]
        );

        return new \WP_REST_Response($updateResult);
    }

    /**
     * Update environment search settings.
     */
    public function updateEnvironmentSettings(\WP_REST_Request $request): \WP_Error|\WP_REST_Response
    {
        $environment = (string) $request->get_param('environment');

        if (false === DeployDI::Config()->get("env.{$environment}", false)) {
            return new \WP_Error(
                'rest_statik_invalid_environment',
                \__('Environment does not exists.', 'statik-search'),
                ['status' => 404]
            );
        }

        $deploymentManager = new DeploymentManager($environment);

        if ($deploymentManager->getLast(DeploymentInterface::IN_PROGRESS)) {
            return new \WP_Error(
                'rest_statik_deployment_in_progress',
                \__('There is an other deployment in progress. Check status and please try again.', 'statik-search'),
                ['status' => 500]
            );
        }

        try {
            $client = new SearchClient($environment);
            $response = $client->updateSettings();
        } catch (\Exception $e) {
            return new \WP_Error(
                'rest_statik_search_error',
                \json_decode($e->getMessage()) ?: $e->getMessage(),
                ['status' => $e->getCode()]
            );
        }

        /**
         * Fire when update triggered by API hook.
         *
         * @param array $updateResult deployment response from provider
         *
         * @return array
         */
        $updateResult = (array) \apply_filters(
            'Statik\Search\deleteSearchResponse',
            [
                'response' => [
                    'success' => true,
                    'raw' => DI::Config()->getValue('settings.debug') ? $response : null,
                    'stage' => 'SEARCH_SETTINGS',
                ],
                'nextAction' => null,
            ]
        );

        return new \WP_REST_Response($updateResult);
    }
}
