<?php

declare(strict_types=1);

use Statik\API\Endpoints\StatikEndpoint;
use Statik\API\Satellites\DeploymentSatellite;
use Statik\API\Satellites\SearchSatellite;

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * Initialize Config constants.
 */
\add_action(
    'mu_plugin_loaded',
    static function (): void {
        /** Load Statik Satellites */
        \array_map(static fn (string $file) => require_once $file, \glob(__DIR__ . '/satellites/*.php'));

        \remove_action('mu_plugin_loaded', 'statik_initialize_constants', 1);

        (new DeploymentSatellite())->printConstants();
        (new SearchSatellite())->printConstants();
    },
    1
);

/**
 * Register all Endpoints classes.
 */
\add_action(
    'rest_api_init',
    static function (): void {
        /** Load REST endpoints */
        \array_map(static fn (string $file) => require_once $file, \glob(__DIR__ . '/endpoints/*.php'));

        $statikTheme = new StatikEndpoint();
        $statikTheme->register_routes();
    },
    1
);
