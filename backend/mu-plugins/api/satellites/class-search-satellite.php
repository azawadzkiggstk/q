<?php

declare(strict_types=1);

namespace Statik\API\Satellites;

/**
 * Class SearchSatellite.
 */
class SearchSatellite extends AbstractSatellite
{
    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'STATIK_SEARCH_CONSTANTS';
    }

    /**
     * {@inheritDoc}
     */
    public function initialize(?array $config): void
    {
        if (null === $config) {
            $this->updateConfig(null);

            return;
        }

        $this->updateConfig([
            'STATIK_SEARCH_SETTINGS' => \json_encode([
                'search' => [
                    'endpoint_url' => [
                        'value' => \sprintf('%s/plugins/search', \untrailingslashit(STATIK_API_ENDPOINT)),
                    ],
                    'client_key' => ['value' => STATIK_API_TOKEN],
                ],
            ]),
        ]);
    }
}
