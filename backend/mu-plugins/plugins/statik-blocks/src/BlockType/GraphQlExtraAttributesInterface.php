<?php

declare(strict_types=1);

namespace Statik\Blocks\BlockType;

/**
 * Interface GraphQlExtraAttributesInterface.
 */
interface GraphQlExtraAttributesInterface
{
    /**
     * Get a list of dynamic extra GraphQL block attributes.
     */
    public function getGraphQlExtraAttributes(): array;
}
