<?php

declare(strict_types=1);

namespace Statik\Deploy\Dashboard\Page;

use Statik\Deploy\Common\Dashboard\Page\AbstractPage;
use Statik\Deploy\DI;

/**
 * Class DeployHistoryPage.
 */
class DeploymentHistoryPage extends AbstractPage
{
    /**
     * {@inheritDoc}
     */
    protected function getPageSettings(): ?array
    {
        return [
            'page_title' => \__('Statik Deployment History', 'statik-deployment'),
            'menu_title' => \__('Deployment History', 'statik-deployment'),
            'capability' => 'manage_options',
            'slug' => 'statik_deployment_history',
            'position' => 2,
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getPageContent(): void
    {
        include_once DI::dir('src/Partials/DeploymentHistoryPage.php');
    }
}
