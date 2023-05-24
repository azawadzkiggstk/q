<?php

declare(strict_types=1);

namespace Statik\Search\Common\Config\Driver;

/**
 * Class DatabaseDriver.
 *
 * Driver using the WordPress database as a source.
 */
class DatabaseDriver extends AbstractDriver
{
    protected string $setFn = 'update_option';
    protected string $getFn = 'get_option';

    /**
     * {@inheritdoc}
     */
    protected function setConfig(array $data, array $expiration = []): bool
    {
        return \call_user_func($this->setFn, $this->namespace, $data, false)
            && \call_user_func($this->setFn, "{$this->namespace}_expiration", $expiration, false);
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfig(): array
    {
        return [
            (array) \call_user_func($this->getFn, $this->namespace, []),
            (array) \call_user_func($this->getFn, "{$this->namespace}_expiration", []),
        ];
    }
}
