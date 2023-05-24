<?php

declare(strict_types=1);

namespace Statik\Menu\Rest\V1;

use Statik\Menu\Common\Rest\ApiInterface;
use Statik\Menu\Common\Rest\Controller\AbstractController;

/**
 * Class Menu.
 */
class Menu extends AbstractController
{
    private bool $isAcf;

    /**
     * {@inheritdoc}
     */
    public function __construct(ApiInterface $api)
    {
        parent::__construct($api);

        $this->rest_base = 'menus';
        $this->isAcf = \class_exists('ACF');
    }

    /**
     * {@inheritdoc}
     */
    public function registerRoutes(): void
    {
        \register_rest_route(
            $this->api->getNamespace(),
            "/{$this->rest_base}",
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'getMenus'],
                'permission_callback' => '__return_true',
                'args' => [
                    'acf' => [
                        'required' => false,
                        'type' => 'bool',
                        'sanitize_callback' => static fn ($value) => \filter_var($value, \FILTER_VALIDATE_BOOLEAN),
                    ],
                ],
            ]
        );
    }

    /**
     * Get all Menus.
     */
    public function getMenus(\WP_REST_Request $request): \WP_REST_Response
    {
        $navMenuLocations = \get_nav_menu_locations();

        foreach (\get_registered_nav_menus() as $location => $id) {
            /** @var \WP_Term $menu */
            $menu = \get_term($navMenuLocations[$location] ?? '', 'nav_menu');

            if (\is_wp_error($menu)) {
                $locations[] = [
                    'id' => null,
                    'location' => $location,
                    'name' => null,
                    'description' => null,
                    'count' => 0,
                    'items' => [],
                ];
                continue;
            }

            $preparedLocation = [
                'id' => $menu->term_id,
                'location' => $location,
                'name' => $menu->name,
                'description' => $menu->description,
                'count' => $menu->count,
                'items' => $this->prepareMenuItems(
                    \wp_get_nav_menu_items($menu),
                    (bool) $request->get_param('acf')
                ),
            ];

            if ($this->isAcf && $request->get_param('acf')) {
                $preparedLocation['acf'] = \get_fields($menu) ?: null;
            }

            $locations[] = $preparedLocation;
        }

        return new \WP_REST_Response($locations ?? []);
    }

    /**
     * Prepare single menu item for display in API.
     */
    private function prepareMenuItems(array $items, bool $acf = false): array
    {
        foreach ($items as $item) {
            /* @var \WP_Post $item */

            $preparedItem = [
                'id' => $item->ID,
                'parent' => (int) $item->menu_item_parent,
                'title' => $item->title,
                'url' => $item->url,
                'target' => $item->target,
                'attr_title' => $item->attr_title,
                'description' => $item->description,
                'classes' => $item->classes,
                'order' => $item->menu_order,
                'xfn' => $item->xfn,
                'megamenu' => \get_nav_menu_item_megamenu($item->ID) ?: null,
            ];

            if ($this->isAcf && $acf) {
                $preparedItem['acf'] = \get_fields($item) ?: null;
            }

            $preparedItems[] = $preparedItem;
        }

        return $preparedItems ?? [];
    }
}
