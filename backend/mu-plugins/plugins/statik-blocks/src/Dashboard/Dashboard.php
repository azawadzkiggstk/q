<?php

declare(strict_types=1);

namespace Statik\Blocks\Dashboard;

use Statik\Blocks\Dashboard\Page\SettingsPage;
use Statik\Blocks\DI;
use Statik\Blocks\Common\Dashboard\AbstractDashboard;
use Statik\Blocks\Common\Utils\NoticeManager;

/**
 * Class Dashboard.
 */
class Dashboard extends AbstractDashboard
{
    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        parent::__construct(DI::url('assets'), DI::dir('assets'));

        $this->saveSettingsHandler();

        \add_action('enqueue_block_editor_assets', [$this, 'enqueueEditorScripts']);
        \add_action('enqueue_block_editor_assets', [$this, 'enqueueEditorStyles']);

        $this->registerPage(SettingsPage::class);
    }

    /**
     * Enqueue required JavaScript scripts for dashboard.
     */
    public function enqueueEditorScripts(): void
    {
        \wp_enqueue_script(
            'statik_blocks_editor',
            \sprintf('%s/../editor/build/index.min.js', $this->assetsUrl),
            ['wp-blocks', 'wp-element', 'wp-editor', 'wp-polyfill'],
            DI::development() ? \mt_rand() : DI::version(),
            true
        );

        \wp_localize_script(
            'statik_blocks_editor',
            'statikBlocks = statikBlocks || {}; statikBlocks.config',
            [
                'coreBlocksEnabled' => DI::coreBlocksEnabled(),
                'saveJsExecution' => 'enable' === DI::Config()->getValue('settings.save_js'),
            ]
        );
    }

    /**
     * Enqueue required CSS for dashboard.
     */
    public function enqueueEditorStyles(): void
    {
        \wp_enqueue_style(
            'statik_blocks_editor',
            \sprintf('%s/../editor/build/index.css', $this->assetsUrl),
            [],
            DI::development() ? \mt_rand() : DI::version()
        );
    }

    /**
     * This is the option save handler.
     */
    private function saveSettingsHandler(): void
    {
        if (empty($_POST['statik_blocks'])) {
            return;
        }

        if (false === \is_admin() || false === \is_user_logged_in()) {
            return;
        }

        if (false === \wp_verify_nonce($_POST['_blocks_nonce'] ?? null, 'statik_blocks_settings_nonce')) {
            NoticeManager::error(\__('Your settings have not been updated. Please try again!', 'statik-blocks'));

            return;
        }

        foreach ($_POST['statik_blocks'] as $key => $value) {
            DI::Config()->set($key, \stripslashes_deep($value));
        }

        DI::Config()->save();

        /**
         * Fire on Save settings action.
         */
        \do_action('Statik\Blocks\onSaveSettings');

        NoticeManager::success(\__('Your settings have been updated.', 'statik-blocks'));
    }
}
