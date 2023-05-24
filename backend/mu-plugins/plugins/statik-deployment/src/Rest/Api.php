<?php

declare(strict_types=1);

namespace Statik\Deploy\Rest;

use Statik\Deploy\Common\Rest\AbstractApi;
use Statik\Deploy\Rest\V1\Deployment;

/**
 * Class Api.
 */
class Api extends AbstractApi
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $namespace)
    {
        parent::__construct($namespace);

        $this->registerController(Deployment::class);
    }
}
