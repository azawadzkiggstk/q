<?php

declare(strict_types=1);

namespace Statik\Blocks;

use Statik\Blocks\Dashboard\Dashboard;

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

        new Updater();

        DI::coreBlocksEnabled() && DI::BlocksManager()->registerBlockTypesDirectory(DI::dir('blocks/core'));
        DI::BlocksManager()->registerBlockTypesDirectory(DI::dir('blocks/statik'));

        \add_action('init', [$this, 'onInit']);
        \add_action('plugins_loaded', [$this, 'initLanguages']);

        /**
         * Fire plugin initialization action.
         */
        \do_action('Statik\Blocks\pluginInit');
    }

    /**
     * Initialize Block Manager service.
     */
    public function onInit(): void
    {
        new Dashboard();
    }

    /**
     * Init Languages support for plugin.
     */
    public function initLanguages(): void
    {
        /** This filter is documented in wp-includes/l10n.php */
        $locale = \apply_filters('plugin_locale', \determine_locale(), 'statik-blocks');
        $path = \dirname($this->path);

        \load_textdomain('statik-blocks', "{$path}/languages/statik-blocks-{$locale}.mo");
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
            \wp_die(\__('The plugin requires at least PHP 8.0', 'statik-blocks'));
        }

        /**
         * Fire plugin activate action.
         */
        \do_action('Statik\Blocks\pluginActivate');
    }

    /**
     * Drop custom database table on plugin deactivation.
     */
    public function onDeactivation(): void
    {
        /**
         * Fire plugin deactivate action.
         */
        \do_action('Statik\Blocks\pluginDeactivate');
    }
}
