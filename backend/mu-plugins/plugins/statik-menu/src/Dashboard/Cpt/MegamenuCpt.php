<?php

declare(strict_types=1);

namespace Statik\Menu\Dashboard\Cpt;

use Statik\Menu\Common\Dashboard\Cpt\AbstractCpt;

/**
 * Class MegamenuCpt.
 */
class MegamenuCpt extends AbstractCpt
{
    public const CPT_SLUG = 'megamenu';

    /**
     * {@inheritdoc}
     */
    public function getCptSettings(): array
    {
        return [
            'labels' => $this->getLabels(),
            'has_archive' => false,
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'supports' => ['title', 'editor'],
            'menu_icon' => 'dashicons-grid-view',
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'show_in_rest' => true,
            'rest_base' => 'megamenus',
            'show_in_graphql' => true,
            'graphql_single_name' => 'megamenu',
            'graphql_plural_name' => 'megamenus',
        ];
    }

    /**
     * Get CPT labels.
     */
    private function getLabels(): array
    {
        return [
            'name' => \__('Megamenus', 'statik-menu'),
            'singular_name' => \__('Megamenus', 'statik-menu'),
            'edit_item' => \__('Edit Megamenu', 'statik-menu'),
            'new_item' => \__('New Megamenu', 'statik-menu'),
            'view_item' => \__('View Megamenu', 'statik-menu'),
            'view_items' => \__('View Megamenus', 'statik-menu'),
            'all_items' => \__('All Megamenus', 'statik-menu'),
            'add_new_item' => \__('Add New Megamenu', 'statik-menu'),
        ];
    }
}
