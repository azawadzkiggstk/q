<?php

declare(strict_types=1);

namespace Statik\Menu\Dashboard\Menu;

use Statik\Menu\Common\Utils\NoticeManager;
use Statik\Menu\Dashboard\Menu\Walker\AcfNavMenuEditWalker;
use Statik\Menu\Dashboard\Menu\Walker\NavMenuEditWalker;
use Statik\Menu\DI;

/**
 * Class Menu.
 */
class Menu
{
    /**
     * Menu constructor.
     */
    public function __construct()
    {
        \add_action('wp_update_nav_menu', [$this, 'updateMenuHandler'], 20);
        \add_filter('wp_edit_nav_menu_walker', [$this, 'getMenuWalker'], 20);
        \add_action('admin_print_footer_scripts-nav-menus.php', [$this, 'getEditModal']);

        \add_action('wp_ajax_statik_menu', [$this, 'handleReloadMenu']);
    }

    /**
     * Get Nav Menu walker.
     */
    public function getMenuWalker(string $currentWalker): string
    {
        return 'ACF_Walker_Nav_Menu_Edit' === $currentWalker
        && \class_exists($currentWalker)
            ? AcfNavMenuEditWalker::class
            : NavMenuEditWalker::class;
    }

    /**
     * Handle changes in menu item.
     */
    public function updateMenuHandler(): void
    {
        if (empty($_POST['menu-item-statik_menu'])) {
            return;
        }

        static $ERROR_ALREADY_RETURNED = false;

        if (false === \wp_verify_nonce($_POST['update-nav-menu-nonce'] ?? null, 'update-nav_menu')) {
            if (false === $ERROR_ALREADY_RETURNED) {
                NoticeManager::error(\__('Your settings have not been updated. Please try again!', 'statik-menu'));
                $ERROR_ALREADY_RETURNED = true;
            }

            return;
        }

        foreach ($_POST['menu-item-statik_menu'] as $postId => $values) {
            $postId = \filter_var($postId, \FILTER_SANITIZE_NUMBER_INT);

            foreach ($values as $name => $value) {
                $name = \filter_var($name);
                $value = \filter_var($value, \FILTER_SANITIZE_NUMBER_INT);

                \update_post_meta($postId, $name, $value);
            }
        }
    }

    /**
     * Render edit Modal.
     */
    public function getEditModal(): void
    {
        include_once DI::dir('src/Partials/Modal.php');
    }

    /**
     * Handle reload menu request.
     */
    public function handleReloadMenu(): void
    {
        if (false === \wp_verify_nonce($_POST['nonce'] ?? null, 'statik_menu_nonce')) {
            \wp_send_json_error(\__('Invalid request nonce. Please reload page and try again!', 'statik-menu'));
        }

        $navMenuId = '{{TEMPLATE_MENU_ID}}';

        \ob_start();

        include_once DI::dir('src/Partials/MenuSelect.php');

        \wp_send_json_success(\ob_get_clean());
    }
}
