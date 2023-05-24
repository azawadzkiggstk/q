<?php

declare(strict_types=1);

namespace Statik\Blocks\Block;

/**
 * Class Blocks.
 */
class Blocks extends \WP_Block_List implements \ArrayAccess
{
    /**
     * Blocks constructor.
     */
    public function __construct(array $blocks, array $availableContext = [], \WP_Block_Type_Registry $registry = null)
    {
        parent::__construct($blocks, $availableContext, $registry);
        $this->blocks = $this->flattenBlocks($blocks);
    }

    /**
     * Flatten blocks data to one level array. For all blocks have been added a
     * uniq ID number and parent ID.
     */
    private function flattenBlocks(array|\WP_Block_List $blocks, ?int $parentId = null): array
    {
        static $id = 0;
        $parsedBlocks = [];

        foreach ($blocks as $block) {
            if (false === $this->registry->is_registered($block->name ?? $block['blockName'] ?? false)) {
                continue;
            }

            $block = new Block(
                $block instanceof \WP_Block ? $block->parsed_block : $block,
                $id++,
                $parentId,
                $this->available_context,
                $this->registry
            );

            $parsedBlocks = $block->inner_blocks
                ? [...$parsedBlocks, $block, ...$this->flattenBlocks($block->inner_blocks, $id - 1)]
                : [...$parsedBlocks, $block];
        }

        $id = null === $parentId ? 0 : $id;

        return $parsedBlocks ?? [];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->blocks[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset): ?Block
    {
        return $this->blocks[$offset];
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function offsetSet($offset, $value): void
    {
        throw new \Exception(\__('Blocks elements are read only and cannot be modified', 'statik-blocks'));
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function offsetUnset($offset): void
    {
        throw new \Exception(\__('Blocks elements are read only and cannot be modified', 'statik-blocks'));
    }
}
