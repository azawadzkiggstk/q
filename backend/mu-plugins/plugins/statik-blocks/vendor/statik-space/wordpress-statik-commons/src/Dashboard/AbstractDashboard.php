<?php

declare(strict_types=1);

namespace Statik\Blocks\Common\Dashboard;

use Statik\Blocks\Common\Common;
use Statik\Blocks\Common\Config\ConfigInterface;
use Statik\Blocks\Common\Dashboard\Cpt\CptInterface;
use Statik\Blocks\Common\Dashboard\Page\NetworkSettingsPage;
use Statik\Blocks\Common\Dashboard\Page\PageInterface;
use Statik\Blocks\Common\Dashboard\Page\SettingsPage;
use Statik\Blocks\Common\Dashboard\Page\SettingsPageInterface;
use Statik\Blocks\Common\Utils\NoticeManager;

/**
 * Class AbstractDashboard.
 */
abstract class AbstractDashboard implements DashboardInterface
{
    protected ConfigInterface $config;

    /**
     * AbstractDashboard constructor.
     */
    public function __construct(
        protected string $assetsUrl,
        protected string $assetsDir,
        protected bool $network = false,
        bool $registerSettings = true
    ) {
        \add_action($this->network ? 'network_admin_menu' : 'admin_menu', [$this, 'registerMainPage'], 1);
        \add_action($this->network ? 'network_admin_menu' : 'admin_menu', [$this, 'menuCleanUp'], \PHP_INT_MAX);

        \add_action('network_admin_notices', [NoticeManager::class, 'display']);
        \add_action('admin_notices', [NoticeManager::class, 'display']);

        $registerSettings && $this->registerPage($this->network ? NetworkSettingsPage::class : SettingsPage::class);
    }

    /**
     * Check if network dashboard.
     */
    public function isNetwork(): bool
    {
        return $this->network;
    }

    /**
     * {@inheritdoc}
     */
    public function registerPage(string $pageClassName): DashboardInterface
    {
        if (\is_a($pageClassName, PageInterface::class, true)) {
            /** @var PageInterface $object */
            $page = new $pageClassName($this);

            if ($page instanceof SettingsPageInterface) {
                $page->getSettingsFields();
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function registerCpt(string $cptClassName): DashboardInterface
    {
        if (\is_a($cptClassName, CptInterface::class, true)) {
            /** @var CptInterface $object */
            new $cptClassName($this);
        }

        return $this;
    }

    /**
     * Register default main page.
     */
    public function registerMainPage(): void
    {
        /**
         * Fire statik menu position filter.
         *
         * @param int $position current position
         *
         * @return int
         */
        $position = (int) \apply_filters('Statik\Common\menuPosition', 2);

        if (\in_array('statik', \array_column($GLOBALS['menu'], 2))) {
            return;
        }

        \add_menu_page(
            \__('Statik', 'statik-commons'),
            \__('Statik', 'statik-commons'),
            'manage_options',
            'statik',
            '__return_empty_string',
            Common::url('assets/images/dot.svg'),
            $position
        );
    }

    /**
     * Remove the menu item from the submenu.
     */
    public function menuCleanUp(): void
    {
        unset($GLOBALS['submenu']['statik'][0]);
    }
}
