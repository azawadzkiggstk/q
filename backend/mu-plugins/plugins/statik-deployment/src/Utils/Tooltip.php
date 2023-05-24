<?php

declare(strict_types=1);

namespace Statik\Deploy\Utils;

/**
 * Class Tooltip.
 */
class Tooltip
{
    /**
     * Print the tooltip.
     */
    public static function add(
        string|int|float $message,
        string|int|float $content = '<span class="dashicons dashicons-info"></span>'
    ): string {
        return \sprintf(
            '<span data-tootik="%s" data-tootik-conf="multiline square\">%s</span>',
            $message,
            $content
        );
    }
}
