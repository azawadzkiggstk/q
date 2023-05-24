<?php

declare(strict_types=1);

namespace Statik\Deploy;

use Illuminate\Support\Arr;
use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\Deployment\Error\DeploymentException;
use Statik\Deploy\Utils\AppPass;

/**
 * Class CronManager.
 */
class CronManager
{
    public const CRON_DEPLOY_JOB_NAME = 'Statik\Deploy\Cron\deploy';
    public const CRON_APP_PASS_JOB_NAME = 'Statik\Deploy\Cron\appPass';

    private bool $doingCron;

    /**
     * CronManager constructor.
     */
    public function __construct()
    {
        $this->doingCron = \defined('DOING_CRON') && DOING_CRON;

        $this->doingCron && \add_action('publish_future_post', [$this, 'executeDeployAfterPublish'], \PHP_INT_MAX);
        $this->doingCron && \add_action('postExpiratorExpire', [$this, 'executeDeployAfterExpire'], \PHP_INT_MAX);
        $this->doingCron && \add_action(self::CRON_DEPLOY_JOB_NAME, [$this, 'executeCustomDeploy'], \PHP_INT_MAX);
        $this->doingCron && \add_action(self::CRON_APP_PASS_JOB_NAME, [$this, 'executeAppPassFlush'], \PHP_INT_MAX);

        // Update cron when save Statik Deploy settings.
        \add_action('Statik\Deploy\onSaveSettings', [$this, 'prepareCustomActionsExecution']);
    }

    /**
     * Execute Deployment after publish post.
     */
    public function executeDeployAfterPublish(): void
    {
        $this->executeDeployAfterTrigger('scheduled');
    }

    /**
     * Execute Deployment after expire post.
     */
    public function executeDeployAfterExpire(): void
    {
        $this->executeDeployAfterTrigger('expired');
    }

    /**
     * Execute Deployment after custom trigger.
     */
    public function executeCustomDeploy(string $environment): void
    {
        try {
            $deploy = new DeploymentManager($environment);
            $deploy->create();
        } catch (DeploymentException) {
        }
    }

    /**
     * Prepare custom action execution.
     */
    public function prepareCustomActionsExecution(): void
    {
        $this->removeCurrentJobs();

        $customActions = (array) (DI::Config()->getValue('cron.actions', []) ?: []);
        $currentTime = \time();

        foreach ($customActions as $key => $action) {
            $environment = Arr::get($action, 'environment.value');
            $schedule = Arr::get($action, 'schedule.value');
            $execution = \strtotime(Arr::get($action, 'execution.value'));

            if ('off' === $schedule) {
                if ($execution <= $currentTime) {
                    unset($customActions[$key]);
                }

                \wp_schedule_single_event($execution, self::CRON_DEPLOY_JOB_NAME, [$environment]);
            } else {
                \wp_schedule_event($execution, $schedule, self::CRON_DEPLOY_JOB_NAME, [$environment]);
            }
        }

        DI::Config()->set('cron.actions.value', $customActions);
        DI::Config()->save();
    }

    /**
     * Execute Deployment after some trigger.
     */
    private function executeDeployAfterTrigger(string $actionType): void
    {
        foreach ((array) (DI::Config()->getValue('cron.scheduled_post', []) ?: []) as $scheduled) {
            if (Arr::get($scheduled, 'action.value') !== $actionType) {
                continue;
            }

            $environment = Arr::get($scheduled, 'environment.value');

            if (empty($environment)) {
                continue;
            }

            try {
                $deploy = new DeploymentManager($environment);
                $deploy->create();
            } catch (DeploymentException) {
            }
        }
    }

    /**
     * Remove all Statik Deploy jobs for prevent keep any removed jobs.
     */
    private function removeCurrentJobs(): void
    {
        $cronJobs = \_get_cron_array();

        foreach ($cronJobs as $time => $jobs) {
            if (\array_key_exists(self::CRON_DEPLOY_JOB_NAME, $jobs)) {
                unset($cronJobs[$time][self::CRON_DEPLOY_JOB_NAME]);
            }
        }

        \_set_cron_array($cronJobs);
    }

    /**
     * Flush App pass credentials.
     */
    public function executeAppPassFlush(int $userId): void
    {
        AppPass::flushCredentials(\get_user_by('id', $userId));
    }
}
