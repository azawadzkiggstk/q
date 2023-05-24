<?php

declare(strict_types=1);

namespace Statik\Search\Common\Dashboard\Page;

/**
 * Interface SettingsPageInterface.
 */
interface SettingsPageInterface
{
    /**
     * Initialize page.
     */
    public function getSettingsFields(): void;
}
