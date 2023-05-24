<?php

declare(strict_types=1);

namespace Statik\Deploy;

use Statik\Deploy\Dashboard\AdminBar;
use Statik\Deploy\Dashboard\Dashboard;
use Statik\Deploy\Dashboard\DeploymentLock;
use Statik\Deploy\Deployment\Logger\PostLogger;
use Statik\Deploy\Preview\Preview;
use Statik\Deploy\Rest\Api;

/**
 * Class Plugin.
 */
class Plugin
{
    /**
     * Plugin constructor.
     *
     * Run all plugin services and register in correct actions and run `init`
     * action when all plugin services are ready.
     */
    public function __construct(private string $path)
    {
        DIProvider::registerGlobalDI();

        \register_activation_hook($path, [$this, 'onActivation']);
        \register_deactivation_hook($path, [$this, 'onDeactivation']);

        new CronManager();
        new Updater();

        \add_action('rest_api_init', [$this, 'onRestApiInit']);
        \add_action('init', [$this, 'onInit']);
        \add_action('plugins_loaded', [$this, 'initLanguages']);

        /**
         * Fire plugin initialization action.
         */
        \do_action('Statik\Deploy\pluginInit');
    }

    /**
     * Run REST API service.
     */
    public function onRestApiInit(): void
    {
        new Api('statik-deployment/v1');
    }

    /**
     * Initialize Dashboard settings page and DeploymentLogger service.
     */
    public function onInit(): void
    {
        new Dashboard();
        new AdminBar();
        new DeploymentLock();
        new PostLogger();
        new Preview();

        DI::Generator()->initializeFields();
    }

    /**
     * Init Languages support for plugin.
     */
    public function initLanguages(): void
    {
        /** This filter is documented in wp-includes/l10n.php */
        $locale = \apply_filters('plugin_locale', \determine_locale(), 'statik-deployment');
        $path = \dirname($this->path);

        \load_textdomain('statik-deployment', "{$path}/languages/statik-deployment-{$locale}.mo");
        \load_textdomain('statik-commons', "{$path}/vendor/statik/common/languages/statik-commons-{$locale}.mo");
    }

    /**
     * Check on plugin activation if the plugin is run on least 8.0 PHP
     * version and if successfully created a custom database table.
     */
    public function onActivation(): void
    {
        if (\PHP_VERSION_ID < 80000) {
            \deactivate_plugins(\plugin_basename($this->path));
            \wp_die(\__('The plugin requires at least PHP 8.0', 'statik-deployment'));
        }

        /**
         * Fire plugin activate action.
         */
        \do_action('Statik\Deploy\pluginActivate');
    }

    /**
     * Drop custom database table on plugin deactivation.
     */
    public function onDeactivation(): void
    {
        /**
         * Fire plugin deactivate action.
         */
        \do_action('Statik\Deploy\pluginDeactivate');
    }
}
