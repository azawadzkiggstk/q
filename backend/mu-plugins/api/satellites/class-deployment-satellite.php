<?php

declare(strict_types=1);

namespace Statik\API\Satellites;

use Statik\Deploy\Deployment\Provider\Statik\Provider;

/**
 * Class DeploymentSatellite.
 */
class DeploymentSatellite extends AbstractSatellite
{
    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'STATIK_DEPLOYMENT_CONSTANTS';
    }

    /**
     * {@inheritDoc}
     */
    public function initialize(?array $config): void
    {
        $constantData = ['env' => []];

        foreach ($config ?? [] as $environment) {
            $constantData['env'][$environment['frontend_id']] = [
                'values' => [
                    'name' => ['value' => $environment['frontend']['display_name']],
                    'provider' => ['value' => Provider::class],
                    'statik' => [
                        'api_url' => [
                            'value' => \sprintf(
                                '%s/companies/%s/projects/%s/backends/%s/frontends/%s/deployments',
                                \untrailingslashit(STATIK_API_ENDPOINT),
                                $environment['company_id'],
                                $environment['project_id'],
                                $environment['backend_id'],
                                $environment['frontend_id'],
                            ),
                        ],
                        'api_token' => ['value' => STATIK_API_TOKEN],
                        'frontend_url' => [
                            'value' => $environment['frontend']['production_url']
                                ?: $environment['frontend']['default_url'],
                        ],
                    ],
                ],
            ];
        }

        $this->updateConfig(['STATIK_DEPLOY_SETTINGS' => $constantData]);
    }
}
