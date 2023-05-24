<?php

declare(strict_types=1);

namespace Statik\Deploy;

use Statik\Deploy\Common\Updater\AbstractUpdater;

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
            'statik_deployment',
            \get_plugin_data(DI::dir('Statik.php'))['Name'],
            DI::version(),
            \is_plugin_active_for_network(\basename(DI::dir()) . '/Statik.php')
        );

        $this->registerUpdate('2.6.0', [$this, 'update260']);
        $this->registerUpdate('3.7.0', [$this, 'update370']);
    }

    /**
     * Update to version 2.6.0.
     *
     * - Add `deploy_id` column in deployment history table.
     * - Add `error` column in deployment history table.
     * - Rename `timeStart` column to `time_start` in deployment history table.
     * - Rename `timeEnd` column to `time_end` in deployment history table.
     */
    protected function update260(): void
    {
        global $wpdb;

        $tableNameLogger = "{$wpdb->base_prefix}statik_deploy_logger";

        $wpdb->query("ALTER TABLE {$tableNameLogger} ADD `deploy_id` text(100)");
        $wpdb->query("ALTER TABLE {$tableNameLogger} ADD `error` varchar(255) DEFAULT NULL");
        $wpdb->query("ALTER TABLE {$tableNameLogger} CHANGE `timeStart` `time_start` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $wpdb->query("ALTER TABLE {$tableNameLogger} CHANGE `timeEnd` `time_end` TIMESTAMP");
    }

    /**
     * Update to version 3.7.0.
     *
     * - Remove `statik_deploy_logger` as is no longer necessary.
     */
    protected function update370(): void
    {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->base_prefix}statik_deploy_logger");
    }
}
