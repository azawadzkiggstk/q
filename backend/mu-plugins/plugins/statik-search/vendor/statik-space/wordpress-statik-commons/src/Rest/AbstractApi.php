<?php

declare(strict_types=1);

namespace Statik\Search\Common\Rest;

use Statik\Search\Common\Rest\Controller\AbstractController;
use Statik\Search\Common\Rest\Controller\ControllerInterface;
use Statik\Search\Common\Rest\Controller\V1\Config;

/**
 * Class AbstractApi.
 */
abstract class AbstractApi implements ApiInterface
{
    /** @var AbstractController[] */
    protected array $registeredControllers = [];

    /**
     * AbstractApi constructor.
     */
    public function __construct(protected string $namespace)
    {
        $this->registerController(Config::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function registerController(string $controllerName, ...$args): ApiInterface
    {
        $controlSum = \md5($controllerName . \json_encode($args));

        if (
            false === \array_key_exists($controlSum, $this->registeredControllers)
            && \is_a($controllerName, ControllerInterface::class, true)
        ) {
            /** @var ControllerInterface $object */
            $object = new $controllerName($this, ...$args);
            $object->registerRoutes();

            $this->registeredControllers[$controlSum] = $object;
        }

        return $this;
    }
}
