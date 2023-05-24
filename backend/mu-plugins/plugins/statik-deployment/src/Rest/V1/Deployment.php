<?php

declare(strict_types=1);

namespace Statik\Deploy\Rest\V1;

use Statik\Deploy\Common\Rest\ApiInterface;
use Statik\Deploy\Common\Rest\Controller\AbstractController;
use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\DI;

/**
 * Class Deploy.
 *
 * The Deployment endpoint allows manage Deployments.
 */
class Deployment extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public function __construct(ApiInterface $api)
    {
        parent::__construct($api);

        $this->rest_base = 'deploy';
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
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => [$this, 'createDeployment'],
                'permission_callback' => [$this, 'checkPermissions'],
                'args' => [
                    'environment' => ['required' => true, 'type' => 'string'],
                    'name' => ['required' => false, 'type' => 'string'],
                    'flushCache' => ['required' => false, 'type' => 'boolean', 'default' => false],
                    'autoRelease' => ['required' => false, 'type' => 'boolean', 'default' => true],
                ],
            ]
        );

        \register_rest_route(
            $this->api->getNamespace(),
            "/{$this->rest_base}/(?P<environment>[\\w\\-\\.]++)",
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'getDeploymentStatus'],
                'permission_callback' => 'is_user_logged_in',
                'args' => [
                    'environment' => ['required' => true, 'type' => 'string'],
                    'id' => ['required' => true, 'type' => 'string'],
                ],
            ]
        );

        \register_rest_route(
            $this->api->getNamespace(),
            "/{$this->rest_base}/(?P<environment>[\\w\\-\\.]++)/log",
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'getDeploymentHistoryLog'],
                'permission_callback' => [$this, 'checkPermissions'],
                'args' => [
                    'environment' => ['required' => true, 'type' => 'string'],
                    'id' => ['required' => true, 'type' => 'string'],
                ],
            ]
        );
    }

    /**
     * Controller trigger deployment in Provider. Response contains additional is
     * information about `Deployment in progress`.
     */
    public function createDeployment(\WP_REST_Request $request)// : \WP_Error|\WP_REST_Response
    {
        $environment = (string) $request->get_param('environment');
        $body = $request->get_body_params();
        $name = \substr($body['name'] ?? '', 0, 20) ?: null;
        $flushCache = (bool) ($body['flushCache'] ?? false);
        $autoRelease = (bool) ($body['autoRelease'] ?? true);

        if (false === DI::Config()->get("env.{$environment}", false)) {
            return new \WP_Error(
                'rest_statik_invalid_environment',
                \__('Environment does not exists.', 'statik-deployment'),
                ['status' => 404]
            );
        }

        try {
            $deployment = new DeploymentManager($environment);
            $deploymentStatus = $deployment->create($name, $flushCache, $autoRelease);
        } catch (\Exception $e) {
            return new \WP_Error(
                'rest_statik_deployment_error',
                \json_decode($e->getMessage()) ?: $e->getMessage(),
                ['status' => $e->getCode() ?: 500]
            );
        }

        /**
         * Fire when deploy triggered by API endpoint.
         *
         * @param array $response deployment response from provider
         *
         * @return array
         */
        $deployResult = (array) \apply_filters(
            'Statik\Deploy\triggerDeploymentResponse',
            [
                'response' => $deploymentStatus->toArray(),
                'nextAction' => [
                    'path' => \add_query_arg(
                        ['id' => $deploymentStatus->getId()],
                        "{$this->api->getNamespace()}/{$this->rest_base}/{$environment}",
                    ),
                    'method' => 'GET',
                    'delay' => 5000,
                ],
            ]
        );

        return new \WP_REST_Response($deployResult);
    }

    /**
     * Controller return details about the last Deployment. Response contains
     * additional is information about `Deployment in progress`.
     */
    public function getDeploymentStatus(\WP_REST_Request $request): \WP_Error|\WP_REST_Response
    {
        $environment = (string) $request->get_param('environment');
        $deployId = (string) $request->get_param('id');

        if (false === DI::Config()->get("env.{$environment}", false)) {
            return new \WP_Error(
                'rest_statik_invalid_environment',
                \__('Environment does not exists.', 'statik-deployment'),
                ['status' => 404]
            );
        }

        try {
            $deployment = new DeploymentManager($environment);
            $deploymentStatus = $deployment->get($deployId);
        } catch (\Exception $e) {
            return new \WP_Error(
                'rest_statik_deployment_error',
                \json_decode($e->getMessage()) ?: $e->getMessage(),
                ['status' => $e->getCode() ?: 500, 'retry' => true]
            );
        }

        if ($deploymentStatus->hasStatus(DeploymentInterface::ERROR)) {
            return new \WP_Error(
                'rest_statik_deployment_error',
                \__('There was an error in the deployment process.', 'statik-deployment'),
                ['status' => 500, 'log' => $deploymentStatus->getId(), 'raw' => $deploymentStatus->getRaw()]
            );
        }

        /**
         * Fire when deploy history check by API endpoint.
         *
         * @param array $response deployment response from provider
         *
         * @return array
         */
        $deployHistory = (array) \apply_filters(
            'Statik\Deploy\historyDeploymentResponse',
            [
                'response' => $deploymentStatus->toArray(),
                'nextAction' => $deploymentStatus->hasStatus(DeploymentInterface::IN_PROGRESS)
                    ? [
                        'path' => \add_query_arg(
                            ['id' => $deploymentStatus->getId()],
                            "{$this->api->getNamespace()}/{$this->rest_base}/{$environment}",
                        ),
                        'method' => 'GET',
                        'delay' => 5000,
                    ]
                    : null,
            ]
        );

        return new \WP_REST_Response($deployHistory);
    }

    /**
     * Controller return Log details about the last Deployment.
     */
    public function getDeploymentHistoryLog(\WP_REST_Request $request): \WP_Error|\WP_REST_Response
    {
        $environment = (string) $request->get_param('environment');
        $deployId = (string) $request->get_param('id');

        if (false === DI::Config()->get("env.{$environment}", false)) {
            return new \WP_Error(
                'rest_statik_invalid_environment',
                \__('Environment does not exists.', 'statik-deployment'),
                ['status' => 404]
            );
        }

        try {
            $deployment = new DeploymentManager($environment);
            $deploymentStatus = $deployment->get($deployId);
        } catch (\Exception $e) {
            return new \WP_Error(
                'rest_statik_log_error',
                \json_decode($e->getMessage()) ?: $e->getMessage(),
                ['status' => $e->getCode()]
            );
        }

        $log = $deploymentStatus->getLogUrl();
        $logContent = $log ? @\file_get_contents($log) : null;

        if (empty($logContent)) {
            return new \WP_Error(
                'rest_statik_log_error',
                \__('Log content cannot be fetched.', 'statik-deployment'),
                ['status' => 500]
            );
        }

        return new \WP_REST_Response(['data' => $logContent]);
    }
}
