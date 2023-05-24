<?php

declare(strict_types=1);

namespace Statik\GraphQL\Dashboard;

use Statik\GraphQL\Common\Dashboard\AbstractDashboard;
use Statik\GraphQL\Common\Utils\NoticeManager;
use Statik\GraphQL\Dashboard\Page\SettingsPage;
use Statik\GraphQL\DI;

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

        \add_action('admin_print_styles', [$this, 'enqueueDashboardStyles']);
        \add_action('admin_print_scripts', [$this, 'enqueueDashboardScripts']);

        $this->registerPage(SettingsPage::class);
    }

    /**
     * Enqueue required Styles for dashboard.
     */
    public function enqueueDashboardStyles(): void
    {
        \wp_enqueue_style(
            'statik_graphql_settings',
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
        \wp_enqueue_script(
            'statik_graphql_settings',
            \sprintf(
                '%s/javascripts/settings.%s',
                $this->assetsUrl,
                DI::development() && \file_exists("{$this->assetsDir}/javascripts/settings.js") ? 'js' : 'min.js'
            ),
            ['jquery', 'wp-polyfill'],
            DI::development() ? \mt_rand() : DI::version(),
            true
        );

        \wp_localize_script(
            'statik_graphql_settings',
            'statikGraphQl = window.statikGraphQl || {}; statikGraphQl.config',
            ['debug' => (int) DI::development()]
        );
    }

    /**
     * This is the option save handler.
     */
    private function saveSettingsHandler(): void
    {
        if (empty($_POST['statik_graphql'])) {
            return;
        }

        if (false === \is_admin() || false === \is_user_logged_in()) {
            return;
        }

        if (false === \wp_verify_nonce($_POST['_graphql_nonce'] ?? null, 'statik_graphql_settings_nonce')) {
            NoticeManager::error(\__('Your settings have not been updated. Please try again!', 'statik-graphql'));

            return;
        }

        foreach ($_POST['statik_graphql'] as $key => $value) {
            DI::Config()->set($key, \stripslashes_deep($value));
        }

        DI::Config()->save();

        /**
         * Fire on Save settings action.
         */
        \do_action('Statik\GraphQL\onSaveSettings');

        NoticeManager::success(\__('Your settings have been updated.', 'statik-graphql'));
    }
}
