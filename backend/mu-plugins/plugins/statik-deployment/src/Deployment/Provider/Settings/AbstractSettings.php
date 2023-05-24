<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Provider\Settings;

use Statik\Deploy\Deployment\Provider\Provider\ProviderInterface;
use Statik\Deploy\DI;

/**
 * Class AbstractSettings.
 *
 * Abstract Provider contains helpful options to use in the Provider.
 */
abstract class AbstractSettings implements SettingsInterface
{
    /**
     * AbstractSettings constructor.
     */
    public function __construct(protected ProviderInterface $provider)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getFields(): array
    {
        $environment = $this->provider->getEnvironment();
        $class = \get_class($this->provider);

        foreach ($this->fields() as $key => $config) {
            $settings["env.{$environment}.values.{$key}"] = \array_merge(
                $config,
                ['conditions' => ["env.{$environment}.values.provider.value" => $class]]
            );
        }

        return $settings ?? [];
    }

    /**
     *  Get a list of raw settings fields for Provider.
     */
    abstract protected function fields(): array;

    /**
     * Get single value.
     */
    public function getValue(string $key, mixed $default = null): mixed
    {
        $environment = $this->provider->getEnvironment();

        return DI::Config()->getValue("env.{$environment}.values.{$key}", $default);
    }
}
