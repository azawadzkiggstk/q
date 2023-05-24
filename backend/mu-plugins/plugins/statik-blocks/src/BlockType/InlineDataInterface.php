<?php

declare(strict_types=1);

namespace Statik\Blocks\BlockType;

/**
 * Interface InlineDataInterface.
 */
interface InlineDataInterface
{
    /**
     * Return an array with all data that could be required on JS file.
     */
    public function getInlineData(): array;
}
