<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Utils;

/**
 * Interface Hookable.
 */
interface Hookable
{
    /**
     * Register hooks with WordPress.
     */
    public function registerHooks(): void;
}
