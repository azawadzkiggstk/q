<?php

declare(strict_types=1);

namespace Statik\Blocks\Common\Dashboard\Page;

use Statik\Blocks\Common\Common;

/**
 * Class NetworkSettingsPage.
 */
class SettingsPage extends AbstractPage
{
    /**
     * {@inheritDoc}
     */
    protected function getPageSettings(): ?array
    {
        /**
         * Fire network settings page position filter.
         *
         * @param int current position
         */
        $position = (int) \apply_filters('Statik\Common\networkSettingsPagePosition', 4);

        return [
            'page_title' => \__('Statik Settings', 'statik-commons'),
            'menu_title' => \__('Settings', 'statik-commons'),
            'capability' => 'manage_options',
            'slug' => 'statik_settings',
            'position' => $position,
        ];
    }

    /**
     * Get settings page and set required variables.
     */
    public function getPageContent(): void
    {
        include Common::dir('/src/Partials/SettingsPage.php');
    }
}
