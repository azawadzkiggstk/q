<?php

declare(strict_types=1);

namespace Statik\Deploy\Dashboard;

use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\Deployment\Logger\PostLogger;
use Statik\Deploy\Deployment\Provider\Deployment\AbstractDeployment;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\DI;

/**
 * Class AdminBar.
 */
class AdminBar
{
    private string $assetsUrl;

    private string $assetsDir;

    private bool $allowUserManage;

    /**
     * AdminBar constructor.
     */
    public function __construct()
    {
        $this->assetsUrl = DI::url('assets');
        $this->assetsDir = DI::dir('assets');
        $this->allowUserManage = \current_user_can('manage_options');

        \add_action('admin_bar_menu', [$this, 'initAdminBarDeploymentDetails'], 100);

        \add_action('admin_print_styles', [$this, 'enqueueAdminBarStyles']);
        \add_action('wp_enqueue_scripts', [$this, 'enqueueAdminBarStyles']);

        \add_action('wp_ajax_statik_admin_bar', [$this, 'handleAjaxAdminBarReload']);
    }

    /**
     * Enqueue required Styles for dashboard.
     */
    public function enqueueAdminBarStyles(): void
    {
        \wp_enqueue_style(
            'statik_deployment_admin_bar',
            \sprintf(
                '%s/stylesheets/admin-bar.%s',
                $this->assetsUrl,
                DI::development() && \file_exists("{$this->assetsDir}/stylesheets/admin-bar.css") ? 'css' : 'min.css'
            ),
            [],
            DI::development() ? \mt_rand() : DI::version()
        );
    }

    /**
     * Handle Ajax request for update AdminBar menu.
     */
    public function handleAjaxAdminBarReload(): void
    {
        $response = ['globalStatus' => 1];

        foreach ((array) DI::Config()->getKeys('env') as $environment) {
            $envVariables = $this->getEnvironmentVariables($environment);
            $response['labels'][$environment] = $this->getLabels($envVariables);
            $response['globalInProgress'] = \max($envVariables['envInProgress'], $response['globalInProgress'] ?? 0);
            $response['globalStatus'] = \max($envVariables['envStatus'], $response['globalStatus']);
        }

        $response['globalInProgress'] = $response['globalInProgress'] ? 'in-progress' : '';
        $response['globalStatus'] = $this->getStatusColor($response['globalStatus']);

        \wp_send_json_success($response);
    }

    /**
     * Initialize Admin Bar menu with last deployment details for all environments.
     */
    public function initAdminBarDeploymentDetails(\WP_Admin_Bar $admin_bar): void
    {
        $environments = (array) DI::Config()->getKeys('env');
        $inProgress = 0;
        $status = 1;

        if (0 === \count($environments)) {
            $settingsUrl = \add_query_arg(
                ['page' => 'statik_settings', 'tab' => 'environments'],
                \admin_url('admin.php')
            );

            $addNewButton = $this->allowUserManage
                ? \sprintf(
                    ' <a href="%s" class="ab-button">%s</a>',
                    $settingsUrl,
                    \__('Add new', 'statik-deployment'),
                )
                : false;

            $admin_bar->add_node([
                'id' => 'statik-deploy-no-env',
                'parent' => 'statik-deploy',
                'title' => $this->loadPartial(
                    'NoEnvironment.php',
                    ['label' => \__('No environment', 'statik-deployment') . $addNewButton]
                ),
                'meta' => [],
            ]);
        }

        foreach ($environments ?? [] as $environment) {
            $deployment = new DeploymentManager($environment);
            $lastDeploy = $deployment->getLast();

            $environmentStatus = $this->getEnvironment(
                $admin_bar,
                $environment,
                $environment === \array_last($environments)
            );

            $inProgress = \max($inProgress, $lastDeploy?->hasStatus(DeploymentInterface::IN_PROGRESS));
            $status = \max($status, $environmentStatus);
        }

        $admin_bar->add_node([
            'id' => 'statik-deploy',
            'title' => $this->loadPartial('MainItem.php', ['status' => $status, 'inProgress' => (bool) $inProgress]),
            'parent' => 'top-secondary',
        ]);
    }

    /**
     * Get admin bar sub menu with details for single environment.
     */
    private function getEnvironment(\WP_Admin_Bar $admin_bar, string $environmentName, bool $last = false): int
    {
        $environmentVariables = $this->getEnvironmentVariables($environmentName);
        $labels = $this->getLabels($environmentVariables);

        $admin_bar->add_node([
            'id' => "statik-deploy-env-{$environmentName}",
            'parent' => 'statik-deploy',
            'title' => $labels['header'],
            'meta' => ['class' => "js-env-{$environmentName} js-label-header"],
        ]);

        $admin_bar->add_node([
            'id' => "statik-deploy-env-{$environmentName}-last",
            'parent' => 'statik-deploy',
            'title' => $labels['last'],
            'meta' => ['class' => "js-env-{$environmentName} js-label-last"],
        ]);

        $admin_bar->add_node([
            'id' => "statik-deploy-env-{$environmentName}-user",
            'parent' => 'statik-deploy',
            'title' => $labels['user'],
            'meta' => ['class' => "js-env-{$environmentName} js-label-user" . ($labels['user'] ? '' : ' hide-menu')],
        ]);

        $admin_bar->add_node([
            'id' => "statik-deploy-env-{$environmentName}-status",
            'parent' => 'statik-deploy',
            'title' => $labels['status'],
            'meta' => ['class' => "js-env-{$environmentName} js-label-status" . ($labels['status'] ? '' : ' hide-menu')],
        ]);

        if (false === $last) {
            $admin_bar->add_node([
                'id' => "statik-deploy-env-{$environmentName}-break",
                'parent' => 'statik-deploy',
                'title' => '<span> </span>',
                'meta' => ['class' => 'break'],
            ]);
        }

        return (int) $environmentVariables['envStatus'];
    }

    /**
     * Get labels for display in Admin Bar.
     */
    private function getLabels(array $environmentVariables): array
    {
        return [
            'header' => $this->loadPartial('ItemHeader.php', $environmentVariables),
            'last' => $this->loadPartial('ItemLastDeploy.php', $environmentVariables),
            'user' => $environmentVariables['envUser']
                ? $this->loadPartial('ItemUser.php', $environmentVariables)
                : null,
            'status' => $environmentVariables['envToDeploy']
                ? $this->loadPartial('ItemStatus.php', $environmentVariables)
                : null,
        ];
    }

    /**
     * Get status color by status level.
     */
    private function getStatusColor(int $statusLevel): string
    {
        return match ($statusLevel) {
            1 => 'green',
            2 => 'orange',
            default => 'red',
        };
    }

    /**
     * Get required variables for single environment.
     */
    private function getEnvironmentVariables(string $environment): array
    {
        $envPageUrl = \get_admin_url(
            null,
            \add_query_arg(
                ['page' => 'statik_deployment', 'env' => $environment, 'tab' => 'changes'],
                'admin.php'
            )
        );

        $initialResponse = [
            'envName' => (string) DI::Config()->get("env.{$environment}.values.name.value", ''),
            'envPageUrl' => $envPageUrl,
        ];

        $deployment = new DeploymentManager($environment);
        $lastDeploy = $deployment->getLast();

        $response = match (true) {
            false === $lastDeploy => $this->prepareNotSupportedEnv(),
            null === $lastDeploy => $this->prepareEmptyEnv($envPageUrl),
            $lastDeploy->hasStatus(DeploymentInterface::IN_PROGRESS) => $this->prepareInProgressEnv(
                $lastDeploy,
                $envPageUrl
            ),
            $lastDeploy->hasStatus(DeploymentInterface::READY) => $this->prepareReadyEnv(
                $lastDeploy,
                $envPageUrl,
                $environment
            ),
            $lastDeploy->hasStatus(DeploymentInterface::ERROR) => $this->prepareErrorEnv(
                $lastDeploy,
                $envPageUrl
            ),
        };

        return \array_merge($initialResponse, $response);
    }

    /**
     * Load Admin Bar template file.
     */
    private function loadPartial(string $path, array $args = []): string
    {
        \extract($args);

        \ob_start();
        include DI::dir("src/Partials/AdminBar/{$path}");

        return (string) \ob_get_clean();
    }

    /**
     * Prepare not supported environment data.
     */
    private function prepareNotSupportedEnv(): array
    {
        return [
            'envInProgress' => 0,
            'envStatus' => 1,
            'envTime' => \__('History not supported', 'statik-deployment'),
            'envUser' => null,
            'envToDeploy' => null,
        ];
    }

    /**
     * Prepare environment where deployment is in progress.
     */
    private function prepareInProgressEnv(AbstractDeployment $deploy, string $envPageUrl): array
    {
        return [
            'envInProgress' => 1,
            'envStatus' => 2,
            /* translators: Deployment page URL for environment */
            'envTime' => \__('Deploying now...', 'statik-deployment'),
            'envUser' => $this->prepareUser($deploy->getMeta('user.email')),
            'envToDeploy' => $this->allowUserManage
                ? \sprintf(
                    '<a href="%s" class="ab-button m-0">%s</a>',
                    $envPageUrl,
                    \__('See progress', 'statik-deployment')
                )
                : false,
        ];
    }

    /**
     * Prepare environment where deployment is done.
     */
    private function prepareReadyEnv(AbstractDeployment $deploy, string $envPageUrl, string $environment): array
    {
        $deployNowButton = $this->allowUserManage
            ? \sprintf(' <a href="%s" class="ab-button">%s</a>', $envPageUrl, \__('Deploy now', 'statik-deployment'))
            : false;
        $toDeploy = \count(PostLogger::getPostsDetails($environment, true));

        return [
            'envInProgress' => 0,
            'envStatus' => $toDeploy ? 2 : 1,
            /* translators: Human readable time since last deployment */
            'envTime' => \sprintf(\__('~%s ago', 'statik-deployment'), \human_time_diff($deploy->getEndTime())),
            'envUser' => $this->prepareUser($deploy->getMeta('user.email')),
            'envToDeploy' => $toDeploy
                ? \sprintf(\_n('%d change', '%d changes', $toDeploy, 'statik-deployment'), $toDeploy) . $deployNowButton
                : \__('No changes', 'statik-deployment'),
        ];
    }

    /**
     * Prepare environment where deployment is finished with error.
     */
    private function prepareErrorEnv(AbstractDeployment $deploy, string $envPageUrl): array
    {
        return [
            'envInProgress' => 0,
            'envStatus' => 3,
            /* translators: Human readable time since last deployment */
            'envTime' => \sprintf(\__('~%s ago', 'statik-deployment'), \human_time_diff($deploy->getEndTime())),
            'envUser' => $this->prepareUser($deploy->getMeta('user.email')),
            'envToDeploy' => $this->allowUserManage
                ? \sprintf(
                    '<a href="%s" class="ab-button m-0">%s</a>',
                    $envPageUrl,
                    \__('Deploy again', 'statik-deployment')
                )
                : false,
        ];
    }

    /**
     * Prepare environment without deployments.
     */
    private function prepareEmptyEnv(string $envPageUrl): array
    {
        return [
            'envInProgress' => 0,
            'envStatus' => 3,
            /* translators: Human readable time since last deployment */
            'envTime' => \__('Not deployed yet', 'statik-deployment'),
            'envUser' => null,
            'envToDeploy' => $this->allowUserManage
                ? \sprintf(
                    '<a href="%s" class="ab-button m-0">%s</a>',
                    $envPageUrl,
                    \__('Deploy now', 'statik-deployment')
                )
                : false,
        ];
    }

    /**
     * Prepare user name.
     */
    private function prepareUser(mixed $user): string
    {
        return match (true) {
            $user instanceof \WP_User => $user->display_name,
            'automatic@statik.space' === $user => \__('[Automatic deployment]', 'statik-deployment'),
            false === empty($user) => $user,
            default => \__('[Unknown user]', 'statik-deployment'),
        };
    }
}
