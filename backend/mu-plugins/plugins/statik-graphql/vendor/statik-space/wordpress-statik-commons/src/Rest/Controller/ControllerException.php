<?php

declare(strict_types=1);

namespace Statik\GraphQL\Common\Rest\Controller;

/**
 * Class ControllerException.
 */
class ControllerException extends \Exception
{
    /**
     * ControllerException constructor.
     *
     * @param mixed $status
     * @param mixed $message
     * @param mixed $code
     */
    public function __construct(
        protected string $status = '',
        string $message = '',
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get Status.
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
