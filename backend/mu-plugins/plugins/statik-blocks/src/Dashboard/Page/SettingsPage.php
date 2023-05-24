<?php

declare(strict_types=1);

namespace Statik\Blocks\Dashboard\Page;

use Statik\Blocks\DI;
use Statik\Blocks\Common\Dashboard\DashboardInterface;
use Statik\Blocks\Common\Dashboard\Page\AbstractPage;
use Statik\Blocks\Common\Dashboard\Page\SettingsPageInterface;

use const Statik\Blocks\ECOSYSTEM;

/**
 * Class SettingsPage.
 */
class SettingsPage extends AbstractPage implements SettingsPageInterface
{
    /**
     * NetworkSettingsPage constructor.
     */
    public function __construct(DashboardInterface $dashboard)
    {
        parent::__construct($dashboard);

        \add_filter('Statik\Common\settingsTabs', [$this, 'addSettingsTab'], 40);
        \add_action('Statik\Common\settingsPageTabs', [$this, 'getSettingsPage'], 40, 2);
    }

    /**
     * Add new tab to the plugins Settings page.
     */
    public function addSettingsTab(array $tabs): array
    {
        return \array_merge($tabs, ['blocks' => 'Gutenberg blocks settings']);
    }

    /**
     * Get custom settings for environment.
     */
    public function getSettingsPage(string $currentTab): void
    {
        'blocks' === $currentTab && include_once DI::dir('src/Partials/SettingsPage.php');
    }

    /**
     * {@inheritDoc}
     */
    public function getSettingsFields(): void
    {
        DI::Generator()->registerFields('blocks_settings', \array_merge(
            [
                'general.heading' => [
                    'type' => 'heading',
                    'label' => \__('General settings', 'statik-auth'),
                ],
                'settings.core_blocks' => ECOSYSTEM
                    ? [
                        'type' => 'input:checkbox',
                        'label' => \__('Core blocks overridden', 'statik-blocks'),
                        'description' => \__(
                            'Use Core blocks overridden by Statik Blocks plugins rather than Gutenberg ones. ' .
                            'This settings can be changed only by <code>STATIK_BLOCKS_DISABLE_CORE</code> constant.',
                            'statik-blocks'
                        ),
                        'values' => ['enable' => ''],
                        'attrs' => ['class' => 'regular-text', 'ui' => true, 'disabled' => true],
                        'value' => ['enable' => DI::coreBlocksEnabled()],
                    ]
                    : [],
                'settings.save_js' => [
                    'type' => 'input:checkbox',
                    'label' => \__('Statik blocks save code execution', 'statik-blocks'),
                    'description' => \__(
                        'Whether <code>save.js</code> code should be executed when save Gutenberg.',
                        'statik-blocks'
                    ),
                    'values' => ['enable' => ''],
                    'attrs' => ['class' => 'regular-text', 'ui' => true],
                ],
                'break' => ['type' => 'break'],
            ],
            DI::BlocksManager()->getSettingsSchema()
        ));
    }
}
