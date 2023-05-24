<?php

declare(strict_types=1);

namespace Statik\Deploy\Common\Dashboard\Page;

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
