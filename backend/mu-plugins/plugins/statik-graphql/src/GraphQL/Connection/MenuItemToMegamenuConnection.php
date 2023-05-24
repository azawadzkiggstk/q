<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Connection;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\AppContext;
use WPGraphQL\Data\Connection\PostObjectConnectionResolver;
use WPGraphQL\Model\MenuItem;

/**
 * Class MenuItemToMegamenuConnection.
 */
class MenuItemToMegamenuConnection implements Hookable
{
    public const CONNECTION_FIELD_NAME = 'megamenu';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerConnection']);
    }

    /**
     * Register connections to Gutenberg Blocks.
     *
     * @throws \Exception
     */
    public function registerConnection(): void
    {
        \register_graphql_connection([
            'fromType' => 'MenuItem',
            'fromFieldName' => self::CONNECTION_FIELD_NAME,
            'toType' => 'Megamenu',
            'oneToOne' => true,
            'resolve' => static function (
                MenuItem $menuItem,
                array $args,
                AppContext $context,
                ResolveInfo $info
            ): ?Deferred {
                $megamenu = \get_nav_menu_item_megamenu($menuItem->databaseId);

                if (0 === $megamenu) {
                    return null;
                }

                $resolver = new PostObjectConnectionResolver($menuItem->databaseId, $args, $context, $info);
                $resolver->set_query_arg('p', $megamenu);

                return $resolver->one_to_one()->get_connection();
            },
        ]);
    }
}
