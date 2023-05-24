<?php

declare(strict_types=1);

namespace Statik\Deploy\Deployment\Provider\Settings;

/**
 * Interface SettingsInterface.
 */
interface SettingsInterface
{
    /**
     * Get a list of settings fields for Provider. This method defined which fields
     * are required. For selects are allowed in the async option that
     * gets some data from API.
     */
    public function getFields(): array;

    /**
     * Return a list of available status steps for the Provider.
     */
    public function statusSteps(): array;
}
