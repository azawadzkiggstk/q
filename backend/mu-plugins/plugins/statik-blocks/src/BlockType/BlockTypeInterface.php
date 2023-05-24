<?php

declare(strict_types=1);

namespace Statik\Blocks\BlockType;

use Statik\Blocks\Block\Block;

/**
 * Interface BlockInterface.
 */
interface BlockTypeInterface
{
    /**
     * Get config value.
     */
    public function getConfig(string $key = null, mixed $default = null): mixed;

    /**
     * Get block path.
     */
    public function getPath(string $subPath = null): string;

    /**
     * Get block URL.
     */
    public function getUrl(string $subPath = null): string;

    /**
     * Get block slug.
     */
    public function getSlug(): string;

    /**
     * Get Block.
     */
    public function getBlock(): ?Block;

    /**
     * Get WP Block type.
     */
    public function getWpBlockType(): ?\WP_Block_Type;
}
