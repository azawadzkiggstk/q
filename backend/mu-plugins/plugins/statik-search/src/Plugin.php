<?php

declare(strict_types=1);

namespace Statik\Search;

use Statik\Search\Dashboard\Dashboard;
use Statik\Search\Rest\Api;
use Statik\Search\Search\Client\SearchClient;
use Statik\Search\Search\SearchManager;

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

        if (false === $this->verifyStatikDeploy()) {
            return;
        }

        \add_action('init', [$this, 'onInit'], 9);
        \add_action('rest_api_init', [$this, 'onRestApiInit'], 9);
        \add_action('plugins_loaded', [$this, 'initLanguages']);

        /**
         * Fire plugin initialization action.
         */
        \do_action('Statik\Search\pluginInit');
    }

    /**
     * Initialize Dashboard settings page and DeploymentLogger service.
     */
    public function onInit(): void
    {
        new Dashboard();
        new SearchManager();

        DI::Generator()->initializeFields();
    }

    /**
     * Run REST API service.
     */
    public function onRestApiInit(): void
    {
        new Api('statik-search/v1');
    }

    /**
     * Init Languages support for plugin.
     */
    public function initLanguages(): void
    {
        /** This filter is documented in wp-includes/l10n.php */
        $locale = \apply_filters('plugin_locale', \determine_locale(), 'statik-search');
        $path = \dirname($this->path);

        \load_textdomain('statik-search', "{$path}/languages/statik-search-{$locale}.mo");
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
            \wp_die(\__('The plugin requires at least PHP 8.0', 'statik-search'));
        }

        /**
         * Fire plugin activate action.
         */
        \do_action('Statik\Search\pluginActivate');
    }

    /**
     * Fire action when plugin is deactivating.
     */
    public function onDeactivation(): void
    {
        if (\class_exists('Statik\Deploy\DI')) {
            foreach ((array) \Statik\Deploy\DI::Config()->get('env', []) as $slug => $env) {
                try {
                    $client = new SearchClient($slug);
                    $client->removeIndex();
                } catch (Search\Client\ClientException) {
                    continue;
                }
            }
        }

        /**
         * Fire plugin deactivate action.
         */
        \do_action('Statik\Search\pluginDeactivate');
    }

    /**
     * Plugin require Statik Deployment Plugin. Check if required one is loaded.
     */
    private function verifyStatikDeploy(): bool
    {
        if (false === \class_exists('Statik\Deploy\Plugin')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';

            \deactivate_plugins(\plugin_basename($this->path));
            \add_action('admin_notices', static function (): void { ?>
                <div class="notice error is-dismissible">
                    <p>
                        <b><?= \__('Statik Search plugin has been disabled!', 'statik-search'); ?></b>
                        <?= \__('Please turn on Statik Deployment plugin and try again!', 'statik-search'); ?></p>
                </div>
            <?php });

            return false;
        }

        return true;
    }
}
