<?php

declare(strict_types=1);

namespace Statik\Menu\Dashboard;

use Statik\Menu\Common\Dashboard\AbstractDashboard;
use Statik\Menu\Dashboard\Cpt\MegamenuCpt;
use Statik\Menu\Dashboard\Menu\Menu;
use Statik\Menu\DI;

/**
 * Class Dashboard.
 */
class Dashboard extends AbstractDashboard
{
    protected Menu $menu;

    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        parent::__construct(DI::url('assets'), DI::dir('assets'));

        $this->menu = new Menu();

        \add_action('admin_print_styles', [$this, 'enqueueDashboardStyles']);
        \add_action('admin_print_scripts', [$this, 'enqueueDashboardScripts']);

        $this->registerCpt(MegamenuCpt::class);
    }

    /**
     * Enqueue required Styles for dashboard.
     */
    public function enqueueDashboardStyles(): void
    {
        $allowPages = ['nav-menus.php', 'post.php', 'post-new.php'];

        if (false === \in_array($GLOBALS['pagenow'] ?? null, $allowPages)) {
            return;
        }

        \wp_enqueue_style(
            'statik_menu_settings',
            \sprintf(
                '%s/stylesheets/settings.%s',
                $this->assetsUrl,
                DI::development() && \file_exists("{$this->assetsDir}/stylesheets/settings.css") ? 'css' : 'min.css'
            ),
            [],
            DI::development() ? \mt_rand() : DI::version()
        );
    }

    /**
     * Enqueue required JavaScript scripts for dashboard.
     */
    public function enqueueDashboardScripts(): void
    {
        if (false === \in_array($GLOBALS['pagenow'] ?? null, ['nav-menus.php', 'post-new.php', 'post.php'])) {
            return;
        }

        \wp_enqueue_script(
            'statik_menu_settings',
            \sprintf(
                '%s/javascripts/settings.%s',
                $this->assetsUrl,
                DI::development() && \file_exists("{$this->assetsDir}/javascripts/settings.js") ? 'js' : 'min.js'
            ),
            ['jquery'],
            DI::development() ? \mt_rand() : DI::version(),
            true
        );

        \wp_localize_script(
            'statik_menu_settings',
            'statikMenu = window.statikMenu || {}; statikMenu.config',
            ['debug' => (int) DI::development(), 'nonce' => \wp_create_nonce('statik_menu_nonce')]
        );
    }
}
