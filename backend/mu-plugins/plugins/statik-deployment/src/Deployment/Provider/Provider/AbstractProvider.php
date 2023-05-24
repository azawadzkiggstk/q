<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Provider\Provider;

use Statik\Deploy\Deployment\Provider\Api\ApiInterface;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\Deployment\Provider\Error\ApiResponseException;
use Statik\Deploy\Deployment\Provider\Error\ProviderException;
use Statik\Deploy\Deployment\Provider\Settings\SettingsInterface;
use Statik\Deploy\DI;

/**
 * Class AbstractDriver.
 *
 * Abstract Provider contains helpful options to use in the Provider.
 */
abstract class AbstractProvider implements ProviderInterface
{
    private static array $memoryCache = [];
    private string $cachePrefix;

    /**
     * AbstractDriver constructor.
     *
     * Initialize the Config field to use across the class.
     */
    public function __construct(
        protected ?string $environment,
        protected ?ApiInterface $api = null,
        protected ?SettingsInterface $settings = null
    ) {
        $this->cachePrefix = "env.{$this->environment}.providerCache";
    }

    /**
     * {@inheritdoc}
     */
    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getApi(): ApiInterface
    {
        return $this->api;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettings(): SettingsInterface
    {
        return $this->settings;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ProviderException
     */
    public function create(
        string $name = null,
        bool $flushCache = false,
        bool $autoRelease = true,
        bool|int $preview = false,
        bool $useCredentials = false
    ): DeploymentInterface {
        try {
            return $this->api->create($name, $flushCache, $autoRelease, $preview, $useCredentials);
        } finally {
            $this->flushCache();
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws ProviderException
     */
    public function get(string $id): DeploymentInterface
    {
        $cacheKey = "{$this->cachePrefix}.get.{$id}";
        $cachedValue = DI::Config()->get($cacheKey, false);

        if ($cachedValue instanceof DeploymentInterface) {
            return $cachedValue;
        }

        if (isset(self::$memoryCache[$cacheKey])) {
            if (self::$memoryCache[$cacheKey] instanceof ApiResponseException) {
                throw self::$memoryCache[$cacheKey];
            }

            return self::$memoryCache[$cacheKey];
        }

        try {
            $deploy = $this->api->get($id);
        } catch (ApiResponseException $e) {
            $this->flushCache();
            self::$memoryCache[$cacheKey] = $e;

            throw $e;
        }

        if ($deploy->hasStatus(DeploymentInterface::IN_PROGRESS)) {
            $this->flushCache();
        } else {
            self::$memoryCache[$cacheKey] = $deploy;
            DI::Config()->set("{$this->cachePrefix}.get.{$id}", $deploy, MINUTE_IN_SECONDS);
            DI::Config()->save();
        }

        return $deploy;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(int $perPage = 20, int $page = 1, bool|int $preview = false): array
    {
        $cacheKey = "{$this->cachePrefix}.fetch.{$page}-{$perPage}-{$preview}";
        $cachedValue = DI::Config()->get($cacheKey, false);

        if (\is_array($cachedValue)) {
            return $cachedValue;
        }

        if (isset(self::$memoryCache[$cacheKey])) {
            if (self::$memoryCache[$cacheKey] instanceof ApiResponseException) {
                throw self::$memoryCache[$cacheKey];
            }

            return self::$memoryCache[$cacheKey];
        }

        try {
            $history = $this->api->fetch($perPage, $page, $preview);
        } catch (ApiResponseException $e) {
            $this->flushCache();
            self::$memoryCache[$cacheKey] = $e;

            throw $e;
        }

        self::$memoryCache[$cacheKey] = $history;
        DI::Config()->set($cacheKey, $history, MINUTE_IN_SECONDS);
        DI::Config()->save();

        return $history;
    }

    /**
     * {@inheritdoc}
     */
    public function rollback(string $id): bool
    {
        try {
            return $this->api->rollback($id);
        } finally {
            $this->flushCache();
        }
    }

    /**
     * Flush provider cache.
     */
    private function flushCache(): void
    {
        DI::Config()->delete($this->cachePrefix);
        DI::Config()->save();
    }
}
