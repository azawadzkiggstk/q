<?php

declare(strict_types=1);

namespace Statik\Blocks\Utils;

class Path
{
    /**
     * Return relative path between two sources.
     */
    public static function relativePath(string $from, string $to, string $separator = \DIRECTORY_SEPARATOR): string
    {
        $from = \str_replace(['/', '\\'], $separator, $from);
        $to = \str_replace(['/', '\\'], $separator, $to);

        $arFrom = \explode($separator, \rtrim($from, $separator));
        $arTo = \explode($separator, \rtrim($to, $separator));
        while (\count($arFrom) && \count($arTo) && ($arFrom[0] == $arTo[0])) {
            \array_shift($arFrom);
            \array_shift($arTo);
        }

        return \str_pad('', \count($arFrom) * 3, '..' . $separator) . \implode($separator, $arTo);
    }
}
