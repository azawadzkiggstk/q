<?php

declare(strict_types=1);

namespace Statik\Deploy\Dashboard;

use Illuminate\Support\Arr;
use Statik\Deploy\Common\Dashboard\AbstractDashboard;
use Statik\Deploy\Common\Utils\NoticeManager;
use Statik\Deploy\Dashboard\Page\DeploymentHistoryPage;
use Statik\Deploy\Dashboard\Page\DeploymentPage;
use Statik\Deploy\Dashboard\Page\SettingsPage;
use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\Deployment\Error\DeploymentException;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\DI;
use Statik\Deploy\Utils\Environment;
use Statik\Deploy\Utils\Tooltip;

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
        $this->switchReleaseHandler();
        $this->emptyEnvironmentsCleanUp();

        \add_action('admin_print_styles', [$this, 'enqueueDashboardStyles']);
        \add_action('admin_print_scripts', [$this, 'enqueueDashboardScripts']);

        \add_action('template_redirect', [$this, 'redirectToFrontendUrl'], 80);

        $this->registerPage(DeploymentPage::class);
        $this->registerPage(DeploymentHistoryPage::class);
        $this->registerPage(SettingsPage::class);
    }

    /**
     * Enqueue required Styles for dashboard.
     */
    public function enqueueDashboardStyles(): void
    {
        \wp_enqueue_style(
            'statik_deployment_settings',
            \sprintf(
                '%s/stylesheets/settings.%s',
                $this->assetsUrl,
                DI::development() && \file_exists("{$this->assetsDir}/stylesheets/settings.css") ? 'css' : 'min.css'
            ),
            [],
            DI::development() ? \mt_rand() : DI::version()
        );
    }

    /**
     * Enqueue required JavaScript scripts for dashboard.
     */
    public function enqueueDashboardScripts(): void
    {
        \wp_enqueue_script(
            'statik_deployment_settings',
            \sprintf(
                '%s/javascripts/settings.%s',
                $this->assetsUrl,
                DI::development() && \file_exists("{$this->assetsDir}/javascripts/settings.js") ? 'js' : 'min.js'
            ),
            ['jquery', 'wp-polyfill'],
            DI::development() ? \mt_rand() : DI::version(),
            true
        );

        foreach ((array) DI::Config()->get('env', []) as $environment => $env) {
            $deployment = new DeploymentManager($environment);
            $inProgress = $deployment->getLast(DeploymentInterface::IN_PROGRESS);

            $environments[$environment] = [
                'slug' => $environment,
                'name' => \ucfirst(Arr::get($env, 'values.name.value', '') ?: ''),
                'inProgress' => $inProgress?->getId(),
            ];
        }

        $environment = Environment::getEnvironmentName();

        \wp_localize_script(
            'statik_deployment_settings',
            'statikDeployment = window.statikDeployment || {}; statikDeployment.config',
            [
                'environments' => \array_values($environments ?? []),
                'currentEnvironment' => $environments[$environment] ?? [],
                'api' => ['base' => \rest_url()],
                'nonce' => \wp_create_nonce('wp_rest'),
                'debug' => (int) DI::development(),
                'preview' => (int) DI::isPreview(),
                'messages' => [
                    'confirmDeploy' => [
                        'title' => \sprintf(
                            /** translators: %s: environment name */
                            \__('Are you sure you want to deploy all changes to %s environment?', 'statik-deployment'),
                            $environments[$environment]['name'] ?? ''
                            ?: \__('[Untitled environment]', 'statik-deployment')
                        ),
                        'name' => \sprintf(
                            '%s %s',
                            \__('Deployment name', 'statik-deployment'),
                            Tooltip::add(
                                \__(
                                    'The deployment name will be displayed in the list of historical deployments and'
                                    . ' can be used to identify the release.',
                                    'statik-deployment'
                                )
                            )
                        ),
                        'flush' => \sprintf(
                            '%s %s',
                            \__('Flush builder cache', 'statik-deployment'),
                            Tooltip::add(
                                \__(
                                    'In some cases, it may be advisable to clear the cache of the build machine so'
                                    . ' that all data from the WP instance is fully fetched. This will extend the'
                                    . ' duration of the deployment.',
                                    'statik-deployment'
                                )
                            )
                        ),
                        'release' => \sprintf(
                            '%s %s',
                            \__('Set as a current release', 'statik-deployment'),
                            Tooltip::add(
                                \__(
                                    'After the deployment is completed, the release will be automatically set as live.',
                                    'statik-deployment'
                                )
                            )
                        ),
                        'confirm' => \__('Deploy', 'statik-deployment'),
                    ],
                    'confirmDelete' => [
                        'title' => \sprintf(
                            /** translators: %s: environment name */
                            \__('Are you sure you want to permanently remove %s environment?', 'statik-deployment'),
                            $environments[$environment]['name'] ?? ''
                            ?: \__('[Untitled environment]', 'statik-deployment')
                        ),
                        'desc' => \__('This action cannot be undone.', 'statik-deployment'),
                        'confirm' => \__('Confirm delete', 'statik-deployment'),
                    ],
                    'confirmRollback' => [
                        'title' => \__(
                            'Are you sure you want to rollback to selected release?',
                            'statik-deployment'
                        ),
                        'desc' => \__('The change will be visible within a few minutes.', 'statik-deployment'),
                        'confirm' => \__('Confirm rollback', 'statik-deployment'),
                    ],
                    'error' => \__(
                        'Request cannot be processed because of an internal API communication error',
                        'statik-deployment'
                    ),
                ],
            ]
        );
    }

    /**
     * This is the option save handler.
     */
    private function saveSettingsHandler(): void
    {
        if (empty($_POST['statik_deployment'])) {
            return;
        }

        if (false === \is_admin() || false === \is_user_logged_in()) {
            return;
        }

        if (false === \wp_verify_nonce($_POST['_deploy_nonce'] ?? null, 'statik_deployment_settings_nonce')) {
            NoticeManager::error(\__('Your settings have not been updated. Please try again!', 'statik-deployment'));

            return;
        }

        foreach ($_POST['statik_deployment'] as $key => $value) {
            DI::Config()->set($key, \stripslashes_deep($value));
        }

        DI::Config()->flushExpirations();
        DI::Config()->save();

        /**
         * Fire on Save settings action.
         */
        \do_action('Statik\Deploy\onSaveSettings');

        NoticeManager::success(\__('Your settings have been updated.', 'statik-deployment'));
    }

    /**
     * Handle switch release form.
     */
    private function switchReleaseHandler(): void
    {
        if (empty($_POST['statik_deployment_release'])) {
            return;
        }

        if (false === \is_admin() || false === \is_user_logged_in()) {
            return;
        }

        if (false === \wp_verify_nonce($_POST['_deploy_release_nonce'] ?? null, 'statik_deployment_release_nonce')) {
            NoticeManager::error(\__('The release has not been changed. Please try again!', 'statik-deployment'));

            return;
        }

        if (false === \current_user_can('manage_options')) {
            NoticeManager::error(
                \__('You are not permitted to do that. Please contact administrator!', 'statik-deployment')
            );

            return;
        }

        $deployId = \filter_var($_POST['statik_deployment_release']['id'] ?? '');
        $environment = \filter_var($_POST['statik_deployment_release']['environment'] ?? '');

        try {
            $deployment = new DeploymentManager($environment);
            $deployment->rollback($deployId);
        } catch (DeploymentException $e) {
            NoticeManager::error(
                \__('The release has not been changed. Error occurred: %s', 'statik-deployment'),
                ["<strong>{$e->getMessage()}</strong>"],
            );

            return;
        }

        /**
         * Fire on Save settings action.
         */
        \do_action('Statik\Deploy\onReleaseSwitch');

        NoticeManager::success(
            \__('The current release has been updated to ID: %s.', 'statik-deployment'),
            ["<strong>{$deployId}</strong>"]
        );
    }

    /**
     * Delete environments that does not have `name` value.
     */
    private function emptyEnvironmentsCleanUp(): void
    {
        foreach ((array) DI::Config()->get('env', []) as $key => $environment) {
            if (Arr::has($environment, 'values.name.value')) {
                continue;
            }

            if (false === empty(Arr::get($environment, 'values.name.value'))) {
                continue;
            }

            DI::Config()->delete("env.{$key}");
            DI::Config()->save();
        }
    }

    /**
     * Redirect view button to frontend environment.
     */
    public function redirectToFrontendUrl(): void
    {
        global $wp;

        if (\is_admin() || \is_front_page()) {
            return;
        }

        $environment = DI::Config()->getValue('settings.preview');
        $deployment = new DeploymentManager($environment);
        $frontendUrl = $deployment->getProvider()?->getFrontUrl();

        if (empty($frontendUrl)) {
            return;
        }

        $requestUrl = \home_url($wp->request);
        $homeUrl = \get_site_url();
        $frontendRequestUrl = \str_replace(\trailingslashit($homeUrl), \trailingslashit($frontendUrl), $requestUrl);

        \wp_redirect(\trailingslashit($frontendRequestUrl));

        exit;
    }
}
