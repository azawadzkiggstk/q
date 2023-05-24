<?php

declare(strict_types=1);

namespace Statik\Deploy\Dashboard\Page;

use Illuminate\Support\Arr;
use Statik\Deploy\Common\Dashboard\DashboardInterface;
use Statik\Deploy\Common\Dashboard\Page\AbstractPage;
use Statik\Deploy\Common\Dashboard\Page\SettingsPageInterface;
use Statik\Deploy\Common\Utils\NoticeManager;
use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\DI;
use Statik\Deploy\Utils\Environment;

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

        \add_filter('Statik\Common\settingsTabs', [$this, 'addSettingsTab'], 10);
        \add_action('Statik\Common\settingsPageTabs', [$this, 'getSettingsPage'], 10, 2);

        $this->saveEnvironmentsActionsHandler();
    }

    /**
     * Add new tab to the plugins Settings page.
     */
    public function addSettingsTab(array $tabs): array
    {
        return \array_merge(
            $tabs,
            ['environments' => 'Deployment environments', 'deployment' => 'Deployment settings']
        );
    }

    /**
     * Get custom settings for environment.
     */
    public function getSettingsPage(string $currentTab): void
    {
        'environments' === $currentTab && include_once DI::dir('src/Partials/SettingsEnvironmentsPage.php');
        'deployment' === $currentTab && include_once DI::dir('src/Partials/SettingsPage.php');
    }

    /**
     * {@inheritDoc}
     */
    public function getSettingsFields(): void
    {
        $deploy = new DeploymentManager();
        $fields = $deploy->getSettingsFields();

        DI::Generator()->registerFields('environments', $fields);

        $environments = (array) DI::Config()->get('env', []);
        $defaultEnvironment = \array_keys($environments)[0] ?? '';

        foreach ($environments as $name => $environment) {
            $environments[$name] = Arr::get($environment, 'values.name.value')
                ?: \__('[Untitled environment]', 'statik-deployment');
        }

        DI::Generator()->registerFields(
            'settings',
            [
                'general.heading' => [
                    'type' => 'heading',
                    'label' => \__('Preview settings', 'statik-auth'),
                ],
                'settings.enable_preview' => [
                    'type' => 'input:checkbox',
                    'label' => \sprintf(
                        '%s <span class="beta">%s</span>',
                        \__('Preview support', 'statik-deployment'),
                        \__('Beta', 'statik-deployment')
                    ),
                    'description' => \__(
                        'Whether preview functionality should be activated.  After clicking the `Preview` button on '
                        . 'the edit screen new fast build will be created.',
                        'statik-deployment'
                    ),
                    'values' => ['enabled' => ''],
                    'default' => 'enabled',
                    'attrs' => ['class' => 'regular-text', 'ui' => true],
                ],
                'settings.preview' => [
                    'type' => 'select',
                    'label' => \__('Preview environment', 'statik-deployment'),
                    'description' => \__(
                        'This environment will be used for the Preview functionality.'
                        . ' This environment will be also opened if the `View` button is clicked.',
                        'statik-deployment'
                    ),
                    'default' => $defaultEnvironment,
                    'values' => $environments,
                    'attrs' => ['class' => 'regular-text'],
                ],
                'logger.break' => [
                    'type' => 'break',
                ],
                'logger.header' => [
                    'type' => 'heading',
                    'label' => \__('Changes logger settings', 'statik-deployment'),
                ],
                'logger.cpt' => [
                    'type' => 'input:checkbox',
                    'label' => \__('Supported custom post types', 'statik-deployment'),
                    'description' => \__(
                        'Posts from selected CPTs will be displayed in the changes table on the Deployment page when '
                        . 'anyone makes any change to them.',
                        'statik-deployment'
                    ),
                    'default' => ['post', 'page', 'megamenu'],
                    'values' => static function (): array {
                        foreach (\get_post_types(['public' => true], 'objects') as $postType) {
                            $postTypes[$postType->name] = $postType->label;
                        }

                        return $postTypes ?? [];
                    },
                ],
                'cron.break' => [
                    'type' => 'break',
                ],
                'cron.heading' => [
                    'type' => 'heading',
                    'label' => \__('Automatic deployments settings', 'statik-deployment'),
                ],
                'cron.scheduled_post' => [
                    'type' => 'repeater',
                    'label' => \__('Trigger deployment', 'statik-deployment'),
                    'description' => \__(
                        'List of triggers that can execute a deployment.',
                        'statik-deployment'
                    ),
                    'fields' => [
                        'environment' => [
                            'type' => 'select',
                            'label' => \__('Environment', 'statik-deployment'),
                            'attrs' => ['required' => 'required'],
                            'default' => $defaultEnvironment,
                            'values' => $environments,
                        ],
                        'action' => [
                            'type' => 'select',
                            'label' => \__('Action', 'statik-deployment'),
                            'attrs' => ['required' => 'required'],
                            'default' => 'scheduled',
                            'values' => static function (): array {
                                $values['scheduled'] = \__('After publishing a scheduled post', 'statik-deployment');

                                if (\defined('POSTEXPIRATOR_VERSION')) {
                                    $values['expired'] = \__('After expired a post', 'statik-deployment');
                                }

                                /**
                                 * Fire CRON actions filter.
                                 *
                                 * @param array $values values
                                 *
                                 * @return array
                                 */
                                return (array) \apply_filters('Statik\Deploy\cronActions', $values ?? []);
                            },
                        ],
                    ],
                ],
                'cron.actions' => [
                    'type' => 'repeater',
                    'label' => \__('Custom actions', 'statik-deployment'),
                    'description' => \__(
                        'Past one-time execution actions will be executed in the next queue and removed from the list.',
                        'statik-deployment'
                    ),
                    'fields' => [
                        'environment' => [
                            'type' => 'select',
                            'label' => \__('Environment', 'statik-deployment'),
                            'attrs' => ['required' => 'required'],
                            'default' => $defaultEnvironment,
                            'values' => $environments,
                        ],
                        'execution' => [
                            'type' => 'input',
                            'label' => \__('First execution', 'statik-deployment'),
                            'description' => \__(
                                'When past date will be provided or left empty, event will be executed in the next queue.',
                                'statik-deployment'
                            ),
                            'attrs' => ['type' => 'datetime-local', 'required' => 'required'],
                        ],
                        'schedule' => [
                            'type' => 'select',
                            'label' => \__('Schedule', 'statik-deployment'),
                            'attrs' => ['required' => 'required'],
                            'default' => 'off',
                            'values' => static function (): array {
                                $values['off'] = \__('Don\'t repeat');
                                foreach (\wp_get_schedules() as $scheduleSlug => $schedule) {
                                    $values[$scheduleSlug] = $schedule['display'];
                                }

                                return $values ?? [];
                            },
                        ],
                    ],
                ],
                'debug.break' => [
                    'type' => 'break',
                ],
                'debug.header' => [
                    'type' => 'heading',
                    'label' => \__('Debug settings', 'statik-deployment'),
                ],
                'settings.debug' => [
                    'type' => 'input:checkbox',
                    'label' => \__('Debug mode', 'statik-deployment'),
                    'description' => \__(
                        'Whether plugin requests should execute in "debug" mode.',
                        'statik-deployment'
                    ),
                    'values' => ['debug' => ''],
                    'attrs' => ['class' => 'regular-text', 'ui' => true],
                ],
                'settings.locker' => [
                    'type' => 'input:checkbox',
                    'label' => \__('Dashboard locker', 'statik-deployment'),
                    'description' => \__(
                        'Whether dashboard locker should be shown. Recommended in Production environment.',
                        'statik-deployment'
                    ),
                    'values' => ['locker' => ''],
                    'default' => 'locker',
                    'attrs' => ['class' => 'regular-text', 'ui' => true],
                ],
            ]
        );
    }

    /**
     * Handle environment adding/deleting forms.
     */
    private function saveEnvironmentsActionsHandler(): void
    {
        if (empty($_POST['statik_deployment_env'])) {
            return;
        }

        if (false === \wp_verify_nonce($_POST['_deploy_env_nonce'] ?? null, 'statik_deployment_env_nonce')) {
            NoticeManager::error(\__('Your settings have not been updated. Please try again!', 'statik-deployment'));

            return;
        }

        $env = \filter_var($_POST['statik_deployment_env']['env']);
        $environmentName = \sanitize_title($env);

        if ('add' === $_POST['statik_deployment_env']['action']) {
            if (DI::Config()->has("env.{$environmentName}")) {
                NoticeManager::error(
                    \__('Environment %s already exists. Please try again with another name!', 'statik-deployment'),
                    ["<strong>{$env}</strong>"]
                );

                \wp_redirect(\add_query_arg('env', $environmentName));
                exit;
            }

            DI::Config()->set("env.{$environmentName}.values.name.value", $env);
            DI::Config()->save();

            NoticeManager::success(
                \__('Environment %s has been created.', 'statik-deployment'),
                ["<strong>{$env}</strong>"]
            );

            /**
             * Fire when environment have been created.
             *
             * @param string $environmentName environment name
             */
            \do_action('Statik\Deploy\createEnvironment', $environmentName);

            \wp_redirect(\add_query_arg('env', $environmentName));
            exit;
        }

        if ('remove' === $_POST['statik_deployment_env']['action']) {
            $envTitle = (string) DI::Config()->get("env.{$environmentName}.values.name.value", $env);
            DI::Config()->delete("env.{$environmentName}");
            DI::Config()->save();

            NoticeManager::success(
                \__('Environment %s has been removed.', 'statik-deployment'),
                ['<strong>' . ($envTitle ?: \__('[Untitled environment]', 'statik-deployment')) . '</strong>']
            );

            /**
             * Fire when environment have been removed.
             *
             * @param string $environmentName environment name
             */
            \do_action('Statik\Deploy\deleteEnvironment', $environmentName);

            \wp_redirect(\remove_query_arg('env'));
            exit;
        }
    }
}
