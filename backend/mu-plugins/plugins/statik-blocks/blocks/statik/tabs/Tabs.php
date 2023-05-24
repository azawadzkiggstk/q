<?php

declare(strict_types=1);

namespace Statik\Blocks\CustomBlocks\Statik\Tabs;

use Statik\Blocks\Block\Block;
use Statik\Blocks\BlockType\AbstractBlockType;
use Statik\Blocks\BlockType\InlineDataInterface;

/**
 * Class Tabs.
 */
class Tabs extends AbstractBlockType implements InlineDataInterface
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
