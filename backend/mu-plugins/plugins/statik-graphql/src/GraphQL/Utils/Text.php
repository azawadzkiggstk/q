<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Utils;

/**
 * Class Text.
 */
class Text
{
    /**
     * Clean unwanted characters from the string and encode UTF-8.
     */
    public static function toUtf8(mixed $text): string
    {
        return \html_entity_decode((string) $text, \ENT_QUOTES, 'UTF-8');
    }
}
