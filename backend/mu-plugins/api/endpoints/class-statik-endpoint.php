<?php

declare(strict_types=1);

namespace Statik\API\Endpoints;

use Statik\API\Satellites\AbstractSatellite;
use Statik\API\Satellites\DeploymentSatellite;
use Statik\API\Satellites\GravityFormsSatellite;
use Statik\API\Satellites\SearchSatellite;

/**
 * Class StatikEndpoint.
 */
class StatikEndpoint extends \WP_REST_Controller
{
    /**
     * {@inheritDoc}
     */
    public function register_routes(): void
    {
        \register_rest_route(
            'statik-luna/v1',
            '/ping',
            [
                'methods' => [\WP_REST_Server::READABLE, \WP_REST_Server::CREATABLE],
                'callback' => [$this, 'triggerUpdate'],
                'permission_callback' => [$this, 'checkPermissions'],
                'args' => ['wait' => ['required' => false, 'type' => 'boolean', 'default' => false]],
            ]
        );
    }

    /**
     * Check if current user has correct capabilities.
     */
    public function checkPermissions(): bool
    {
        return \current_user_can('manage_options');
    }

    /**
     * Trigger update endpoint.
     */
    public function triggerUpdate(\WP_REST_Request $request): \WP_Error|\WP_REST_Response
    {
        \ignore_user_abort(true);

        if (false === $request->get_param('wait') && \function_exists('fastcgi_finish_request')) {
            echo \json_encode(['status' => true]);

            \fastcgi_finish_request();
        }

        $this->maintenanceMode(true);
        $result = $this->updateSatellitesFromApi();
        $this->maintenanceMode(false);

        if (\is_wp_error($result)) {
            return $result;
        }

        return new \WP_REST_Response(['success' => true]);
    }

    /**
     * Toggle maintenance mode for the site. Creates/deletes the maintenance
     * file to enable/disable maintenance mode.
     */
    private function maintenanceMode(bool $enable): void
    {
        $file = ABSPATH . '.maintenance';

        if (\file_exists($file)) {
            @\unlink($file);
        }

        if (false === $enable) {
            return;
        }

        @\file_put_contents(
            $file,
            '<?php $upgrading = ' . \time() - 9 * MINUTE_IN_SECONDS . '; ?>',
            \fileperms(ABSPATH . 'index.php') & 0777 | 0644
        );
    }

    /**
     * Handle update satellites from Statik API. Get all data from API and
     * rebuild satellites in the WordPress instance.
     */
    private function updateSatellitesFromApi(): ?\WP_Error
    {
        $rawResponse = \wp_remote_get(
            \sprintf('%s/plugins/wp/satellites', \untrailingslashit(STATIK_API_ENDPOINT)),
            ['headers' => ['authorization' => \sprintf('Bearer %s', STATIK_API_TOKEN)]]
        );

        if (\is_wp_error($rawResponse)) {
            return $rawResponse;
        }

        $response = \json_decode(\wp_remote_retrieve_body($rawResponse), true) ?: [];

        if (200 !== \wp_remote_retrieve_response_code($rawResponse)) {
            return new \WP_Error(
                \strtolower($response['error'] ?? 'internal_error'),
                $response['message'] ?? 'There has been a critical error on this website.'
            );
        }

        $plugins = ['sentry' => null, 'forms' => null, 'search' => null];

        foreach ($response as $environment) {
            foreach ($environment['plugins'] ?? [] as $plugin => $enable) {
                if ($enable && null === ($plugins[$plugin] ?? null) && isset($environment['settings'][$plugin])) {
                    $plugins[$plugin] = $environment['settings'][$plugin];
                } else {
                    $plugins[$plugin] ??= null;
                }
            }
        }

        $this->getSatellite('deployment')->initialize($response);

        foreach ($plugins as $plugin => $config) {
            $satellite = $this->getSatellite($plugin);
            $satellite && $satellite->initialize($config);
        }

        return null;
    }

    /**
     * Get list of satellites support.
     */
    private function getSatellite(string $name): ?AbstractSatellite
    {
        $satellites = [
            'deployment' => [DeploymentSatellite::class, 'instance'],
            'forms' => [GravityFormsSatellite::class, 'instance'],
            'search' => [SearchSatellite::class, 'instance'],
        ];

        return isset($satellites[$name]) ? $satellites[$name]() : null;
    }
}
