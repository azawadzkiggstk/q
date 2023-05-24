<?php

declare(strict_types=1);

namespace Statik\Menu\Rest;

use Statik\Menu\Common\Rest\AbstractApi;
use Statik\Menu\Rest\V1\Menu;

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

        $this->registerController(Menu::class);
    }
}
