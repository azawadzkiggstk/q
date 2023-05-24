<?php

declare(strict_types=1);

namespace Statik\Search\Dashboard\Page;

use Statik\Search\Common\Dashboard\DashboardInterface;
use Statik\Search\Common\Dashboard\Page\AbstractPage;
use Statik\Search\DI;

/**
 * Class DeploymentPage.
 */
class DeploymentPage extends AbstractPage
{
    /**
     * DeploymentPage constructor.
     */
    public function __construct(DashboardInterface $dashboard)
    {
        parent::__construct($dashboard);

        \add_action('Statik\Deploy\deploymentPageButtons', [$this, 'getSettingsPage']);
        \add_action('Statik\Deploy\deploymentStatusSteps', [$this, 'addStatusSteps']);
    }

    /**
     * Get settings page and set required variables.
     */
    public function getSettingsPage(?string $environmentName): void
    {
        include_once DI::dir('src/Partials/DeploymentPage.php');
    }

    /**
     * Add custom status steps.
     */
    public function addStatusSteps(array $steps): array
    {
        return \array_merge(
            $steps,
            [
                'SEARCH_DELETING' => [
                    'spacer' => true,
                    'type' => 'search',
                    'name' => 'Delete search',
                    'label' => \__('Delete old posts data from search engine', 'statik-search'),
                    'tooltip' => \__('The search engine is cleaned up from old records.', 'statik-search'),
                ],
                'SEARCH_UPDATING' => [
                    'type' => 'search',
                    'name' => 'Update search',
                    'label' => \__('Upload posts data to search engine', 'statik-search'),
                    'tooltip' => \__(
                        'The search engine replaced with up-to-date information on all supported CPTs.',
                        'statik-search'
                    ),
                ],
                'SEARCH_SETTINGS' => [
                    'type' => 'search',
                    'name' => 'Update settings',
                    'label' => \__('Update search engine behavioral settings', 'statik-search'),
                    'tooltip' => \__(
                        'Search engine is updated with new settings from the Settings tab.',
                        'statik-search'
                    ),
                ],
            ]
        );
    }
}
