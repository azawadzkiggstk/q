<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Field;

use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\Model\Menu;

/**
 * Class MenuIsHierarchicalField.
 */
class MenuIsHierarchicalField implements Hookable
{
    public const FIELD_NAME = 'isHierarchical';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerConnection']);
    }

    /**
     * Register connections to 'is hierarchical' field.
     *
     * @throws \Exception
     */
    public function registerConnection(): void
    {
        \register_graphql_field(
            'menu',
            self::FIELD_NAME,
            [
                'type' => ['non_null' => 'Boolean'],
                'description' => \__('Whether the object is hierarchical.', 'statik-graphql'),
                'resolve' => static function (Menu $menu): bool {
                    return (bool) \array_filter(
                        \wp_get_nav_menu_items($menu->menuId),
                        static fn (\WP_Post $post) => $post->menu_item_parent > 0
                    );
                },
            ]
        );
    }
}
