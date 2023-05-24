<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment;

use Statik\Deploy\Deployment\Error\DeploymentException;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\Deployment\Provider\Error\ApiRequestException;
use Statik\Deploy\Deployment\Provider\Error\ApiResponseException;
use Statik\Deploy\Deployment\Provider\Error\ProviderException;
use Statik\Deploy\Deployment\Provider\Provider\ProviderInterface;
use Statik\Deploy\DI;
use Statik\Deploy\Utils\Environment;

/**
 * Class DeployManager.
 *
 * The DeployManager class is responsible for manage the deployment process
 * with an active Provider.
 */
class DeploymentManager
{
    /** @var array List of default Providers */
    private const PROVIDERS = [
        Provider\Statik\Provider::class => Provider\Statik\Provider::PROVIDER_NAME,
    ];

    private ?ProviderInterface $provider;

    /**
     * Deployment constructor.
     *
     * If Provider is not passed then get active Provider from Config.
     */
    public function __construct(private ?string $environment = null)
    {
        $this->environment = $this->environment ?: Environment::getEnvironmentName();
        $this->provider = $this->getProviderFromConfig($this->environment);
    }

    /**
     * Get a list of available Provider. Allow adding customs Provider using
     * WP filter. All Provider required to implement the `DriverInterface` interface.
     */
    public static function getProviders(): array
    {
        /**
         * Fire Deploy Custom Providers filter.
         *
         * @param array $customProviders list of custom providers
         *
         * @return array
         */
        $customProviders = (array) \apply_filters('Statik\Deploy\customProviders', []);

        return \array_merge($customProviders, static::PROVIDERS);
    }

    /**
     * Get active Provider instance. It could be `null` when no option selected in Config.
     */
    public function getProvider(): ?ProviderInterface
    {
        return null !== $this->provider ? $this->provider : null;
    }

    /**
     * Get Environment name.
     */
    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    /**
     * Get all available status steps.
     */
    public function getStatusSteps(): array
    {
        /**
         * Fire Status steps filter.
         *
         * @param array             $status   status steps from provider
         * @param ProviderInterface $provider currently selected provider
         *
         * @return array
         */
        $statusSteps = (array) \apply_filters(
            'Statik\Deploy\deploymentStatusSteps',
            $this->provider?->getSettings()->statusSteps() ?: [],
            $this->provider
        );

        return \array_merge(
            $statusSteps,
            [
                'ERROR' => [
                    'type' => 'error',
                    'label' => \__('The request was terminated due to an error', 'statik-deployment'),
                ],
            ]
        );
    }

    /**
     * Trigger a Provider deployment process.
     *
     * @throws DeploymentException
     */
    public function create(
        string $name = null,
        bool $flushCache = false,
        bool $autoRelease = true,
        bool|int $preview = false,
        bool $useCredentials = false
    ): DeploymentInterface {
        if (null === $this->provider) {
            throw new DeploymentException(\__('Missing or invalid deployment provider.', 'statik-deployment'), 400);
        }

        /**
         * Fire before Deployment action.
         *
         * @param string $environment current environment name
         */
        \do_action('Statik\Deploy\beforeDeployment', $this->environment);

        try {
            $response = $this->provider?->create($name, $flushCache, $autoRelease, $preview, $useCredentials);
        } catch (ApiRequestException|ApiResponseException $e) {
            throw new DeploymentException($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

    /**
     * Get deployment details from Provider.
     *
     * @throws DeploymentException
     */
    public function get(string $id): DeploymentInterface
    {
        if (null === $this->provider) {
            throw new DeploymentException(\__('Missing or invalid deployment provider.', 'statik-deployment'), 400);
        }

        try {
            $response = $this->provider->get($id);
        } catch (ApiRequestException $e) {
            throw new DeploymentException($e->getMessage(), $e->getCode(), $e);
        } catch (ApiResponseException $e) {
            /**
             * Fire after Deployment error action.
             *
             * @param string               $environment current environment name
             * @param ApiResponseException $error       error instance
             */
            \do_action(
                'Statik\Deploy\afterDeploymentError',
                ['environment' => $this->environment, 'error' => $e]
            );

            throw new DeploymentException($e->getMessage(), $e->getCode(), $e);
        }

        if ($response->hasStatus(DeploymentInterface::READY)) {
            /**
             * Fire after Deployment successful action.
             *
             * @param string              $environment current environment name
             * @param DeploymentInterface $response    API response instance
             */
            \do_action(
                'Statik\Deploy\afterDeploymentSuccess',
                ['environment' => $this->environment, 'response' => $response]
            );
        }

        return $response;
    }

    /**
     * Get a list of the latest deployments.
     *
     * @throws DeploymentException
     */
    public function fetch(int $perPage = 20, int $page = 1, bool|int $preview = false): array
    {
        if (null === $this->provider) {
            throw new DeploymentException(\__('Missing or invalid deployment provider.', 'statik-deployment'), 400);
        }

        try {
            return $this->provider->fetch($perPage, $page, $preview);
        } catch (ProviderException $e) {
            throw new DeploymentException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * Switch the live deployment.
     *
     * @throws DeploymentException
     */
    public function rollback(string $id): bool
    {
        if (null === $this->provider) {
            throw new DeploymentException(\__('Missing or invalid deployment provider.', 'statik-deployment'), 400);
        }

        try {
            $this->provider->rollback($id);
        } catch (ApiRequestException|ApiResponseException $e) {
            throw new DeploymentException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        return true;
    }

    /**
     * Get the deployment in progress.
     */
    public function getLast(string $status = null, bool|int $preview = false): ?DeploymentInterface
    {
        try {
            $history = $this->fetch(preview: $preview);
            $deploys = $history['data'] ?? [];

            if ($status) {
                $deploys = \array_filter(
                    $deploys,
                    static fn (DeploymentInterface $response) => $response->hasStatus($status)
                );
            }

            return \count($deploys) ? $deploys[0] : null;
        } catch (DeploymentException) {
            return null;
        }
    }

    /**
     * Get a list of settings fields for Deployment. This is one always required
     * field with a list of Provider. The rest of the fields are getting
     * from active Provider.
     */
    public function getSettingsFields(): array
    {
        $providersConfigs = [];

        foreach (\array_keys(self::getProviders()) as $provider) {
            /** @var ProviderInterface $provider */
            $provider = new $provider($this->environment);

            $providersConfigs = \array_merge($providersConfigs, $provider->getSettings()->getFields());
        }

        return \array_merge(
            [
                "env.{$this->environment}.header" => [
                    'type' => 'heading',
                    'label' => \__('Environment settings', 'statik-deployment'),
                ],
                "env.{$this->environment}.values.name" => [
                    'type' => 'input',
                    'label' => \__('Environment name', 'statik-deployment'),
                    'description' => \__(
                        'The display name is used to identify the environment within the dashboard.',
                        'statik-deployment'
                    ),
                    'attrs' => ['class' => 'regular-text', 'required' => 'required'],
                ],
                "env.{$this->environment}.values.provider" => [
                    'type' => 'select',
                    'label' => \__('Provider', 'statik-deployment'),
                    'description' => \__(
                        'The selected provider is responsible for managing the deployment process.',
                        'statik-deployment'
                    ),
                    'values' => [static::class, 'getProviders'],
                    'attrs' => ['class' => 'regular-text', 'required' => 'required'],
                ],
            ],
            $providersConfigs
        );
    }

    /**
     * Utils for check provider from config.
     */
    private function getProviderFromConfig(string $environment = null): ?ProviderInterface
    {
        $environment = $environment ?: Environment::getEnvironmentName();
        $providerName = DI::Config()->getProvider("env.{$environment}.values.provider.value");

        if (\is_a($providerName, ProviderInterface::class, true)) {
            return new $providerName($environment);
        }

        return null;
    }
}
