<?php

declare(strict_types=1);

namespace Statik\Blocks\BlockType;

/**
 * Interface EditorInlineDataInterface.
 */
interface EditorInlineDataInterface
{
    /**
     * Return an array with all data that could be required on JS file.
     */
    public function getEditorInlineData(): array;
}
