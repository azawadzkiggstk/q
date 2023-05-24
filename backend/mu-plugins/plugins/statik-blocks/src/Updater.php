<?php

declare(strict_types=1);

namespace Statik\Blocks;

use Statik\Blocks\Common\Updater\AbstractUpdater;

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
            'statik_blocks',
            \get_plugin_data(DI::dir('Statik.php'))['Name'],
            DI::version(),
            false
        );

        $this->registerUpdate('3.5.0', [$this, 'update350']);
    }

    /**
     * Update to version 3.5.0.
     *
     * - Replace `statik-core` blocks to `core`.
     */
    protected function update350(): void
    {
        global $wpdb;

        $results = $wpdb->get_results("SELECT ID, post_content FROM {$wpdb->posts} WHERE post_content LIKE '<!-- %'");

        foreach ($results as $post) {
            $count = 0;
            $content = \str_replace(
                ['wp:statik-core/', 'wp-block-statik-core-'],
                ['wp:', 'wp-block-'],
                $post->post_content,
                $count
            );

            if (0 === $count) {
                continue;
            }

            $wpdb->update($wpdb->posts, ['post_content' => $content], ['ID' => $post->ID]);
        }
    }
}
