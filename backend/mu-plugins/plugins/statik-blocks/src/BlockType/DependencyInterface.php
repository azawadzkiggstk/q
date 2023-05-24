<?php

declare(strict_types=1);

namespace Statik\Blocks\BlockType;

/**
 * Interface DependencyInterface.
 */
interface DependencyInterface
{
    /**
     * Determinate whether the Blocks have some requirements and can be enabled.
     */
    public function haveDependencies(): bool;
}
