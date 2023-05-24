<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Connection;

use GraphQL\Deferred;
use Statik\GraphQL\GraphQL\Type\Field\ThemeGlobalSettingsField;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use Statik\GraphQL\GraphQL\Utils\Relay;
use WPGraphQL\Model\Theme;

/**
 * Class GlobalSettingsToThemeConnection.
 */
class GlobalSettingsToThemeConnection implements Hookable
{
    public const CONNECTION_FIELD_NAME = 'globalSettings';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerConnection']);
    }

    /**
     * Register connections to Theme.
     *
     * @throws \Exception
     */
    public function registerConnection(): void
    {
        \register_graphql_connection([
            'fromType' => 'Theme',
            'fromFieldName' => self::CONNECTION_FIELD_NAME,
            'toType' => ThemeGlobalSettingsField::FIELD_NAME,
            'oneToOne' => true,
            'resolve' => fn (Theme $theme): Deferred => $this->getGlobalSettingsNode($theme),
        ]);
    }

    /**
     * Get Global Settings node.
     */
    private function getGlobalSettingsNode(Theme $theme): Deferred
    {
        $fn = static fn (): string => $theme->slug;
        /**
         * Override default `stylesheet` to get data for specific theme.
         */
        \add_filter('stylesheet', $fn, 100);
        \add_filter('template', $fn, 100);
        \WP_Theme_JSON_Resolver::clean_cached_data();
        $globalSettings = \wp_get_global_settings();
        \remove_filter('stylesheet', $fn, 100);
        \remove_filter('template', $fn, 100);

        return new Deferred(fn (): array => [
            'node' => [
                'id' => Relay::toGlobalId(self::CONNECTION_FIELD_NAME, $theme->slug),
                'raw' => static fn (): string => (string) \json_encode($globalSettings),
                'border' => fn (): ?string => $this->getSettingsValue($globalSettings, 'border'),
                'color' => fn (): ?string => $this->getSettingsValue($globalSettings, 'color'),
                'spacing' => fn (): ?string => $this->getSettingsValue($globalSettings, 'spacing'),
                'typography' => fn (): ?string => $this->getSettingsValue($globalSettings, 'typography'),
                'blocks' => fn (): ?string => $this->getSettingsValue($globalSettings, 'blocks'),
                'custom' => fn (): ?string => $this->getSettingsValue($globalSettings, 'custom'),
                'layout' => fn (): ?string => $this->getSettingsValue($globalSettings, 'layout'),
            ],
        ]);
    }

    /**
     * Get value from Theme's Global Settings.
     */
    private function getSettingsValue(array $settings, string $key): ?string
    {
        return isset($settings[$key]) ? (string) \json_encode($settings[$key]) : null;
    }
}
