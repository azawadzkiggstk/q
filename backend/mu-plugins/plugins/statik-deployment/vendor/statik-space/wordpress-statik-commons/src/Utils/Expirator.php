<?php

declare(strict_types=1);

namespace Statik\Deploy\Common\Utils;

use Illuminate\Support\Arr;

/**
 * Class Expirator.
 */
class Expirator
{
    /**
     * Filter expiration dates.
     */
    public static function filter(array $data, array $expirations, bool $flush = false): array
    {
        $now = $flush ? \PHP_INT_MAX : \time();
        $expirations = Arr::dot($expirations);

        foreach ($expirations as $key => $time) {
            if ($time > $now) {
                continue;
            }

            $data = Arr::except($data, $key);
            unset($expirations[$key]);
        }

        return [$data, Arr::undot($expirations)];
    }
}
