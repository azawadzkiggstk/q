<?php

declare(strict_types=1);

namespace Statik\Menu\Common\Utils;

/**
 * Class ArrayUtils.
 */
class ArrayUtils
{
    /**
     * Recursive array diff.
     */
    public static function deepDiff(array $array1, array $array2): array
    {
        foreach ($array1 as $key => $value) {
            if (\array_key_exists($key, $array2)) {
                if (\is_array($value)) {
                    $recursiveDiff = self::deepDiff($value, $array2[$key]);

                    if (\count($recursiveDiff)) {
                        $result[$key] = $recursiveDiff;
                    }
                } elseif ($value !== $array2[$key]) {
                    $result[$key] = $value;
                }
            } else {
                $result[$key] = $value;
            }
        }

        return $result ?? [];
    }
}
