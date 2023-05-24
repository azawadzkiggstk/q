<?php

declare(strict_types=1);

namespace Statik\Deploy\Common\Dashboard\Cpt;

/**
 * Interface CptInterface.
 */
interface CptInterface
{
    /**
     * Get single CPT settings.
     */
    public function getCptSettings(): array;
}
