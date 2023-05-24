<?php

declare(strict_types=1);

namespace Statik\Search\Search;

use Statik\Search\DI;
use Statik\Search\Search\Client\ClientException;
use Statik\Search\Search\Client\SearchClient;

/**
 * Class SearchManager.
 */
class SearchManager
{
    /**
     * SearchManager constructor.
     */
    public function __construct()
    {
        if (
            empty(DI::Config()->getValue('search.endpoint_url'))
            || empty(DI::Config()->getValue('search.client_name'))
            || empty(DI::Config()->getValue('search.client_key'))
        ) {
            return;
        }

        \add_action('Statik\Deploy\deleteEnvironment', [$this, 'onEnvironmentDelete']);
        \add_action('Statik\Deploy\afterDeploymentSuccess', [$this, 'onDeploymentSuccessful']);
    }

    /**
     * Action on environment delete, remove then all data from search.
     */
    public function onEnvironmentDelete(string $environmentName): bool
    {
        try {
            $client = new SearchClient($environmentName);
            $response = $client->removeIndex();
        } catch (ClientException) {
            return false;
        }

        return $response['acknowledged'] ?? false;
    }

    /**
     * Action on successful deployment, upload then all of changed posts.
     */
    public function onDeploymentSuccessful(array $deploymentDetails): void
    {
        \add_filter(
            'Statik\Deploy\historyDeploymentResponse',
            static function (array $deployHistory) use ($deploymentDetails) {
                $deployHistory['response']['stage'] = 'SEARCH_DELETING';
                $deployHistory['nextAction'] = [
                    'path' => "statik-search/v1/search/{$deploymentDetails['environment']}",
                    'method' => 'DELETE',
                    'delay' => 0,
                    'data' => [],
                ];

                return $deployHistory;
            }
        );
    }

    /**
     * Get the site prefix.
     */
    public static function getSitePrefix(): string
    {
        $path = \is_multisite() && \function_exists('get_blog_details')
            ? \get_blog_details()->path ?? '/'
            : '/';

        /**
         * Fire custom site prefix filters.
         *
         * @param string $path path for current site
         *
         * @return string
         */
        return (string) \apply_filters('Statik\Search\customSitePrefix', $path);
    }
}
