<?php

declare(strict_types=1);

namespace Statik\GraphQL\Dashboard\Page;

use Statik\GraphQL\Common\Dashboard\DashboardInterface;
use Statik\GraphQL\Common\Dashboard\Page\AbstractPage;
use Statik\GraphQL\Common\Dashboard\Page\SettingsPageInterface;
use Statik\GraphQL\DI;

/**
 * Class NetworkSettingsPage.
 */
class SettingsPage extends AbstractPage implements SettingsPageInterface
{
    /**
     * NetworkSettingsPage constructor.
     */
    public function __construct(DashboardInterface $dashboard)
    {
        parent::__construct($dashboard);

        \add_filter('Statik\Common\settingsTabs', [$this, 'addSettingsTab'], 25);
        \add_action('Statik\Common\settingsPageTabs', [$this, 'getSettingsPage'], 25, 2);
    }

    /**
     * Add new tab to the plugins Settings page.
     */
    public function addSettingsTab(array $tabs): array
    {
        return \array_merge($tabs, ['graphql' => 'GraphQL settings']);
    }

    /**
     * Get settings page and set required variables.
     */
    public function getSettingsPage(string $currentTab): void
    {
        'graphql' === $currentTab && include_once DI::dir('src/Partials/SettingsPage.php');
    }

    /**
     * Get custom settings for environment.
     */
    public function getSettingsFields(): void
    {
        DI::Generator()->registerFields(
            'graphql_settings',
            [
                'query.heading' => [
                    'type' => 'heading',
                    'label' => \__('Query settings', 'statik-auth'),
                ],
                'settings.default_query_limit' => [
                    'type' => 'input',
                    'label' => \__('Default query amount', 'statik-graphql'),
                    'description' => \__(
                        'Filter the default number of results per page that should be queried.',
                        'statik-graphql'
                    ),
                    'default' => 1000,
                    'attrs' => ['class' => 'regular-text', 'type' => 'number', 'required' => true, 'min' => 1],
                ],
                'settings.max_query_limit' => [
                    'type' => 'input',
                    'label' => \__('Maximum query amount', 'statik-graphql'),
                    'description' => \__(
                        'Filter the maximum number of results per page that can be queried.',
                        'statik-graphql'
                    ),
                    'default' => 1000,
                    'attrs' => ['class' => 'regular-text', 'type' => 'number', 'required' => true, 'min' => 1],
                ],
                'settings.break' => [
                    'type' => 'break',
                ],
                'debug.heading' => [
                    'type' => 'heading',
                    'label' => \__('Debug settings', 'statik-auth'),
                ],
                'settings.debug' => [
                    'type' => 'input:checkbox',
                    'label' => \__('Debug mode', 'statik-graphql'),
                    'description' => \__(
                        'Whether plugin requests should execute in "debug" mode. This setting is disabled if'
                        . ' <code>GRAPHQL_DEBUG</code> is defined in <code>wp-config.php</code> file.',
                        'statik-graphql'
                    ),
                    'values' => ['debug' => ''],
                    'attrs' => ['class' => 'regular-text', 'ui' => true],
                ],
                'settings.tracing' => [
                    'type' => 'input:checkbox',
                    'label' => \__('Tracing', 'statik-graphql'),
                    'description' => \__(
                        'Adds trace data to the extensions portion of GraphQL responses',
                        'statik-graphql'
                    ),
                    'values' => ['tracing' => ''],
                    'attrs' => ['class' => 'regular-text', 'ui' => true],
                ],
                'settings.logs' => [
                    'type' => 'input:checkbox',
                    'label' => \__('Query logs', 'statik-graphql'),
                    'description' => \__('This is a debug tool that can have an impact on performance and is'
                        . ' not recommended to have active in production. Adds SQL Query logs to the extensions portion'
                        . ' of GraphQL responses.', 'statik-graphql'),
                    'values' => ['logs' => ''],
                    'attrs' => ['class' => 'regular-text', 'ui' => true],
                ],
            ],
        );
    }
}
