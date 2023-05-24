<?php

declare(strict_types=1);

namespace Statik\Menu\Common\Dashboard;

/**
 * Interface DashboardInterface.
 */
interface DashboardInterface
{
    /**
     * Register page.
     */
    public function registerPage(string $pageClassName): self;

    /**
     * Register Custom Post Type.
     */
    public function registerCpt(string $cptClassName): self;
}
