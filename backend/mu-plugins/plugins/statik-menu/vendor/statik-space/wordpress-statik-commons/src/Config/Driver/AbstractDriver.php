<?php

declare(strict_types=1);

namespace Statik\Menu\Common\Config\Driver;

use Statik\Menu\Common\Utils\Expirator;

/**
 * Class AbstractDriver.
 *
 * Class contains all methods required in Driver.
 */
abstract class AbstractDriver implements DriverInterface
{
    /**
     * AbstractDriver constructor.
     */
    public function __construct(protected string $namespace)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setInSource(array $data, array $expiration = []): bool
    {
        return $this->setConfig(...Expirator::filter($data, $expiration));
    }

    /**
     * {@inheritdoc}
     */
    public function getFromSource(): array
    {
        return Expirator::filter(...$this->getConfig());
    }

    /**
     * Get config value from the source.
     */
    abstract protected function getConfig(): array;

    /**
     * Set config value in the source.
     */
    abstract protected function setConfig(array $data, array $expiration = []): bool;
}
