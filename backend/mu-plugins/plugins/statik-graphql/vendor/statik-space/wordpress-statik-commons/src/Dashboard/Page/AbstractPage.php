<?php

declare(strict_types=1);

namespace Statik\GraphQL\Common\Dashboard\Page;

use Statik\GraphQL\Common\Dashboard\DashboardInterface;

/**
 * Class AbstractPage.
 */
abstract class AbstractPage implements PageInterface
{
    /**
     * AbstractPage constructor.
     */
    public function __construct(protected DashboardInterface $dashboard)
    {
        \add_action($this->dashboard->isNetwork() ? 'network_admin_menu' : 'admin_menu', [$this, 'initPage']);
    }

    /**
     * {@inheritdoc}
     */
    public function initPage(): void
    {
        global $submenu;

        $settings = $this->getPageSettings();

        if (null === $settings || \in_array($settings['slug'], \array_column($submenu['statik'] ?? [], 2))) {
            return;
        }

        \add_submenu_page(
            'statik',
            $settings['page_title'],
            $settings['menu_title'],
            $settings['capability'],
            $settings['slug'],
            fn () => $this->getPageContent(),
            $settings['position'] ?? 10
        );
    }

    /**
     * Get page content.
     */
    protected function getPageContent(): void
    {
    }

    /**
     * Get settings for Page.
     */
    protected function getPageSettings(): ?array
    {
        return null;
    }
}
