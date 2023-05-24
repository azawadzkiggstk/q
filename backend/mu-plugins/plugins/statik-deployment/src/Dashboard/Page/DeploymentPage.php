<?php

declare(strict_types=1);

namespace Statik\Deploy\Dashboard\Page;

use Statik\Deploy\Common\Dashboard\Page\AbstractPage;
use Statik\Deploy\DI;

/**
 * Class DeploymentPage.
 */
class DeploymentPage extends AbstractPage
{
    /**
     * {@inheritDoc}
     */
    protected function getPageSettings(): ?array
    {
        return [
            'page_title' => \__('Statik Deployment', 'statik-deployment'),
            'menu_title' => \__('Deployment', 'statik-deployment'),
            'capability' => 'manage_options',
            'slug' => 'statik_deployment',
            'position' => 1,
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getPageContent(): void
    {
        include_once DI::dir('src/Partials/DeploymentPage.php');
    }
}
