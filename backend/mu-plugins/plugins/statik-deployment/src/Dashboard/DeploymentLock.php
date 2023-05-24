<?php

declare(strict_types=1);

namespace Statik\Deploy\Dashboard;

use Statik\Deploy\Deployment\DeploymentManager;
use Statik\Deploy\Deployment\Provider\Deployment\DeploymentInterface;
use Statik\Deploy\DI;
use Statik\Deploy\Utils\User;

/**
 * Class DeploymentLock.
 */
class DeploymentLock
{
    /**
     * DeploymentLock constructor.
     */
    public function __construct()
    {
        if (empty(DI::Config()->getValue('settings.locker'))) {
            return;
        }

        \add_filter('heartbeat_send', [$this, 'showDeploymentDetails']);
        \add_action('admin_footer', [$this, 'showDeploymentLockPopup']);
    }

    /**
     * Show info if currently is any deployment in progress in the WP heartbeat.
     */
    public function showDeploymentDetails(array $response): array
    {
        foreach ((array) DI::Config()->getKeys('env') as $environment) {
            $deployment = new DeploymentManager($environment);
            $deployInProgress = $deployment->getLast(DeploymentInterface::IN_PROGRESS);

            if (empty($deployInProgress)) {
                continue;
            }

            $userName = User::resolve($deployInProgress->getMeta('user.email'));
            $inProgress['deploy'] = ['slug' => $environment, 'id' => $deployInProgress->getId()];

            $inProgress['env'] = [
                'slug' => $environment,
                'name' => DI::Config()->get(
                    "env.{$environment}.values.name.value",
                    \__('[Untitled environment]', 'statik-deployment')
                ),
                'url' => \get_admin_url(
                    null,
                    \add_query_arg(
                        ['page' => 'statik_deployment', 'env' => $environment, 'tab' => 'changes'],
                        'admin.php'
                    )
                ),
            ];

            $inProgress['labels'] = [
                'title' => \sprintf(
                    /* translators: 1 - User name, 2 - Environment name */
                    \__(
                        'The deployment performed by %1$s to the %2$s environment is currently in progress.',
                        'statik-deployment'
                    ),
                    "<i>{$userName}</i>",
                    "<i>{$inProgress['env']['name']}</i>"
                ),
                'avatar' => \sprintf('<img src="%s" alt="%s"/>', \get_avatar_url($deploy['user']->ID ?? 0), $userName),
                'steps' => $this->getDeploymentLockPopupSteps($environment, $deployInProgress),
            ];

            break;
        }

        if (isset($inProgress)) {
            $response['statikDeploymentInProgress'] = $inProgress;
        }

        return $response;
    }

    /**
     * Show deployment lock popup.
     */
    public function showDeploymentLockPopup(): void
    {
        require_once DI::dir('src/Partials/DeploymentLockPopup.php');
    }

    /**
     * Get deployment lock popup steps.
     */
    private function getDeploymentLockPopupSteps(string $environment, DeploymentInterface $deployInProgress): string
    {
        $deployment = new DeploymentManager($environment);
        $rawDeploymentSteps = \array_filter(
            $deployment->getStatusSteps(),
            static fn (array $config): bool => 'deployment' === $config['type']
        );
        $deploymentSteps = \array_keys($rawDeploymentSteps);
        $currentStageIndex = \array_search($deployInProgress->getStage(), $deploymentSteps);

        $steps = '';

        foreach ($deploymentSteps as $index => $step) {
            if (false === isset($rawDeploymentSteps[$step]['name'])) {
                continue;
            }

            $steps .= \sprintf(
                '<li class="%s">%s</li>',
                $index === $currentStageIndex ? 'in-progress' : ($index < $currentStageIndex ? 'success' : ''),
                \str_replace('_', ' ', $rawDeploymentSteps[$step]['name']),
            );
        }

        return $steps;
    }
}
