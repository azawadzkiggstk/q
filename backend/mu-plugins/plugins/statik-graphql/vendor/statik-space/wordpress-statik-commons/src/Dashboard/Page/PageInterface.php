<?php

declare(strict_types=1);

namespace Statik\GraphQL\Common\Dashboard\Page;

/**
 * Interface PageInterface.
 */
interface PageInterface
{
    /**
     * Initialize page in the WP.
     */
    public function initPage(): void;
}
