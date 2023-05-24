<?php

declare(strict_types=1);

namespace Statik\Blocks\BlockType;

/**
 * Interface GraphQlConnectionsInterface.
 */
interface GraphQlConnectionsInterface
{
    /**
     * Get dynamic GraphQL connections.
     */
    public function getGraphQlConnections(): array;
}
