<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Admin;

use Statik\GraphQL\DI;
use Statik\GraphQL\GraphQL\Admin\GraphiQL\GraphiQL;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;

/**
 * Class Admin.
 */
class Admin
{
    /**
     * Admin constructor.
     */
    public function __construct()
    {
        \add_filter('graphql_show_admin', '__return_false');
        \add_filter('graphql_get_setting_section_field_value', [$this, 'overrideSettings'], 10, 3);

        \add_filter('graphql_connection_max_query_amount', [$this, 'overrideMaxQueryAmount']);
        \add_filter('graphql_connection_amount_requested', [$this, 'overrideDefaultQueryAmount'], 10, 2);
        \add_filter('graphql_post_object_connection_query_args', [$this, 'overrideDefaultObjectQueryAmount'], 10, 3);
        \add_filter('graphql_comment_connection_query_args', [$this, 'overrideDefaultObjectQueryAmount'], 10, 3);
        \add_filter('graphql_term_object_connection_query_args', [$this, 'overrideDefaultObjectQueryAmount'], 10, 3);

        \add_filter('graphql_endpoint', static fn () => 'graphql');

        (new GraphiQL())->init();
    }

    /**
     * Override WpGraphQL plugin settings.
     */
    public function overrideSettings(mixed $value, mixed $default, string $optionName): mixed
    {
        $override = [
            'graphiql_enabled' => 'on',
            'show_graphiql_link_in_admin_bar' => 'on',
            'public_introspection_enabled' => 'on',
            'restrict_endpoint_to_logged_in_users' => 'off',
            'delete_data_on_deactivate' => 'off',
            'debug_mode_enabled' => DI::Config()->getValue('settings.debug') ? 'on' : 'off',
            'tracing_enabled' => DI::Config()->getValue('settings.tracing') ? 'on' : 'off',
            'query_logs_enabled' => DI::Config()->getValue('settings.logs') ? 'on' : 'off',
        ];

        if (isset($override[$optionName])) {
            $value = $override[$optionName];
        }

        /**
         * Fire override settings filter.
         *
         * @param mixed  $value      field value
         * @param mixed  $default    default value
         * @param string $optionName option name
         *
         * @return mixed
         */
        return \apply_filters('Statik\GraphQL\overrideSettings', $value, $default, $optionName);
    }

    /**
     * Override WpGraphQL plugin max query amount.
     */
    public function overrideMaxQueryAmount(): int
    {
        $amount = (int) DI::Config()->getValue('settings.max_query_limit', 1000);

        /**
         * Fire override max query amount filter.
         *
         * @param int $amount amount value
         *
         * @return int
         */
        return (int) \apply_filters('Statik\GraphQL\overrideMaxQueryAmount', $amount);
    }

    /**
     * Override WpGraphQL plugin default query amount.
     */
    public function overrideDefaultQueryAmount(int $amount, AbstractConnectionResolver $resolver): int
    {
        $args = $resolver->get_args();

        if ($amount !== (int) ($args['first'] ?? 0) && $amount !== (int) ($args['last'] ?? 0)) {
            $amount = (int) DI::Config()->getValue('settings.default_query_limit', 1000);
        }

        /**
         * Fire override default query amount filter.
         *
         * @param int                        $amount   amount value
         * @param AbstractConnectionResolver $resolver resolver instance
         *
         * @return int
         */
        return (int) \apply_filters('Statik\GraphQL\overrideDefaultQueryAmount', $amount, $resolver);
    }

    /**
     * Override WpGraphQL plugin default object query amount.
     */
    public function overrideDefaultObjectQueryAmount(array $queryArgs, mixed $source, array $args): array
    {
        $amount = $queryArgs['posts_per_page'] ?? $queryArgs['number'] ?? 11;

        if (empty($args['last']) && empty($args['first'])) {
            $amount = (int) DI::Config()->getValue('settings.default_query_limit', 1000) + 1;
        }

        /**
         * Fire override default object query amount filter.
         *
         * @param int   $amount amount value
         * @param array $args   query arguments
         *
         * @return array
         */
        $amount = (int) \apply_filters('Statik\GraphQL\overrideDefaultQueryAmount', (int) $amount, $args);

        if (isset($queryArgs['posts_per_page'])) {
            $queryArgs['posts_per_page'] = $amount;
        } elseif (isset($queryArgs['number'])) {
            $queryArgs['number'] = $amount;
        }

        return $queryArgs;
    }
}
