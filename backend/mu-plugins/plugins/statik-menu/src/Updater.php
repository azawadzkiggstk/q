<?php

declare(strict_types=1);

namespace Statik\Menu;

use Statik\Menu\Common\Updater\AbstractUpdater;

/**
 * Class Updater.
 */
class Updater extends AbstractUpdater
{
    /**
     * Updater constructor.
     */
    public function __construct()
    {
        /** WordPress Plugin Administration API */
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        parent::__construct(
            'statik_menu',
            \get_plugin_data(DI::dir('Statik.php'))['Name'],
            DI::version(),
            false
        );
    }
}
