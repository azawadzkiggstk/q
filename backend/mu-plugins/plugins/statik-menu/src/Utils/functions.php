<?php

declare(strict_types=1);

/**
 * Get Menu item has Megamenu page.
 *
 * @param int $menu_item_id menu item ID
 */
function get_nav_menu_item_megamenu(int $menu_item_id): int
{
    return (int) \get_post_meta($menu_item_id, 'megamenu-page', true);
}

/**
 * Check if Menu has item with Megamenu page.
 */
function has_nav_menu_megamenu_item(int $menu_id): bool
{
    $items = \wp_get_nav_menu_items($menu_id, ['output' => OBJECT]);

    if (false === $items) {
        return false;
    }

    foreach ($items as $item) {
        if (\get_nav_menu_item_megamenu($item->ID) > 0) {
            return true;
        }
    }

    return false;
}

/**
 * Get array with Blocks when Menu item has Megamenu page.
 */
function get_nav_menu_item_megamenu_blocks(int $menu_item_id): ?array
{
    $megamenu = \get_nav_menu_item_megamenu($menu_item_id);

    if (0 === $megamenu || 'publish' !== \get_post($megamenu)->post_status) {
        return null;
    }

    if (false === \class_exists('Statik\Gutenberg\Rest\V1\GutenbergBlock')) {
        return null;
    }

    return (array) \get_post_meta($megamenu, Statik\Gutenberg\Rest\V1\GutenbergBlock::META_NAME, true);
}
