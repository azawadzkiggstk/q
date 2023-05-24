<?php

declare(strict_types=1);

namespace Statik\Search\Rest;

use Statik\Search\Common\Rest\AbstractApi;
use Statik\Search\Rest\V1\Search;

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

        $this->registerController(Search::class);
    }
}
