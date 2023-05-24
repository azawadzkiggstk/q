<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Admin\GraphiQL;

/**
 * Class GraphiQL.
 */
class GraphiQL extends \WPGraphQL\Admin\GraphiQL\GraphiQL
{
    /**
     * Register the admin page as a subpage.
     */
    public function register_admin_page(): void
    {
        \add_submenu_page(
            'statik',
            \__('GraphiQL IDE', 'statik-graphql'),
            \__('GraphiQL IDE', 'statik-graphql'),
            'manage_options',
            'graphiql-ide',
            [$this, 'render_graphiql_admin_page']
        );
    }
}
