<?php

declare(strict_types=1);

namespace Statik\Search\Dashboard;

use Illuminate\Support\Arr;
use Statik\Search\Common\Dashboard\AbstractDashboard;
use Statik\Search\Common\Utils\NoticeManager;
use Statik\Deploy\DI as DeployDI;
use Statik\Deploy\Utils\Environment;
use Statik\Search\Dashboard\Page\DeploymentPage;
use Statik\Search\Dashboard\Page\SettingsPage;
use Statik\Search\DI;

/**
 * Class Dashboard.
 */
class Dashboard extends AbstractDashboard
{
    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        parent::__construct(DI::url('assets'), DI::dir('assets'));

        $this->saveSettingsHandler();

        \add_action('admin_print_scripts', [$this, 'enqueueDashboardScripts']);

        $this->registerPage(SettingsPage::class);
        $this->registerPage(DeploymentPage::class);
    }

    /**
     * Enqueue required JavaScript scripts for dashboard.
     */
    public function enqueueDashboardScripts(): void
    {
        \wp_enqueue_script(
            'statik_search_settings',
            \sprintf(
                '%s/javascripts/settings.%s',
                $this->assetsUrl,
                DI::development() && \file_exists("{$this->assetsDir}/javascripts/settings.js") ? 'js' : 'min.js'
            ),
            ['jquery', 'wp-polyfill', 'statik_deployment_settings'],
            DI::development() ? \mt_rand() : DI::version(),
            true
        );

        $environment = Environment::getEnvironmentName(($_GET['page'] ?? null) === 'statik_deployment');
        $environments = [];

        foreach ((array) DeployDI::Config()->get('env', []) as $slug => $env) {
            $environments[$slug] = ['slug' => $slug, 'name' => \ucfirst(Arr::get($env, 'values.name.value', '') ?: '')];
        }

        \wp_localize_script(
            'statik_search_settings',
            'statikSearch = window.statikSearch || {}; statikSearch.config',
            [
                'debug' => (int) DI::development(),
                'environments' => \array_values($environments ?? []),
                'currentEnvironment' => $environments[$environment] ?? [],
                'messages' => [
                    'confirmSearch' => [
                        'title' => \sprintf(
                            \__('Are you sure you want to update Search engine for %s environment?', 'statik-search'),
                            $environments[$environment]['name'] ?? ''
                        ),
                        'desc' => \sprintf(
                            \__('The browser window must remain open until completed.', 'statik-search'),
                            $environments[$environment]['name'] ?? ''
                        ),
                        'confirm' => \__('Update search', 'statik-search'),
                    ],
                ],
            ]
        );
    }

    /**
     * This is the option save handler.
     */
    private function saveSettingsHandler(): void
    {
        if (empty($_POST['statik_search'])) {
            return;
        }

        if (false === \is_admin() || false === \is_user_logged_in()) {
            return;
        }

        if (false === \wp_verify_nonce($_POST['_search_nonce'] ?? null, 'statik_search_settings_nonce')) {
            NoticeManager::error(\__('Your settings have not been updated. Please try again!', 'statik-search'));

            return;
        }

        foreach ($_POST['statik_search'] as $key => $value) {
            DI::Config()->set($key, \stripslashes_deep($value));
        }

        DI::Config()->save();

        NoticeManager::success(\__('Your settings have been updated.', 'statik-search'));
    }
}
