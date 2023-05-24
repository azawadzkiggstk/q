<?php

declare(strict_types=1);

namespace Statik\Blocks\CustomBlocks\Statik\Spacer;

use Statik\Blocks\Block\Block;
use Statik\Blocks\BlockType\AbstractBlockType;
use Statik\Blocks\BlockType\InlineDataInterface;

/**
 * Class Spacer.
 */
class Spacer extends AbstractBlockType implements InlineDataInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInlineData(): array
    {
        return [
            'attributes' => static fn (Block $block) => $block->attributes,
        ];
    }
}
