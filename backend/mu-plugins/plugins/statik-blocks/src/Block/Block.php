<?php

declare(strict_types=1);

namespace Statik\Blocks\Block;

use Illuminate\Support\Arr;
use Statik\Blocks\BlockType\BlockTypeInterface;
use Statik\Blocks\BlockType\SettingsInterface;
use Statik\Blocks\DI;

/**
 * Class Block.
 */
class Block extends \WP_Block
{
    private BlockTypeInterface|\WP_Block_Type|null $blockType;

    /**
     * Block constructor.
     */
    public function __construct(
        array $block,
        public int $id,
        public ?int $parentId,
        array $availableContext = [],
        \WP_Block_Type_Registry $registry = null
    ) {
        parent::__construct($block, $availableContext, $registry);

        $this->blockType = DI::BlocksManager()->getBlockType($this->name)
            ?: \WP_Block_Type_Registry::get_instance()->get_registered($this->name);
    }

    /**
     * Get context.
     */
    public function getContext(string $key = null, mixed $default = null): mixed
    {
        return $key ? Arr::get($this->available_context, $key, $default) : $this->available_context;
    }

    /**
     * Get block attribute.
     */
    public function getAttributes(string $key = null, mixed $default = null): mixed
    {
        return $key ? Arr::get($this->attributes, $key, $default) : $this->attributes;
    }

    /**
     * Get settings values.
     */
    public function getSettings(string $key = null, mixed $default = null): mixed
    {
        if (false === $this->blockType instanceof SettingsInterface) {
            return $default;
        }

        $slug = $this->blockType->getSlug();

        if ($key) {
            return DI::Config()->get("{$slug}.{$key}.value", $default);
        }

        $blockType = $this->blockType;

        /** @var SettingsInterface $blockType */
        foreach ($blockType->getSettings() as $key => $setting) {
            $blockSettings[$key] = DI::Config()->get("{$slug}.{$key}.value");
        }

        return $blockSettings ?? [];
    }

    /**
     * Get block type instance.
     */
    public function getBlockType(): BlockTypeInterface|\WP_Block_Type|null
    {
        return $this->blockType;
    }
}
