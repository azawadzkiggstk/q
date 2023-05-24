<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Field;

use GraphQL\Deferred;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\Model\Theme;

/**
 * Class ThemeGlobalStylesheetsField.
 */
class ThemeGlobalStylesheetsField implements Hookable
{
    public const FIELD_NAME = 'globalStylesheets';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'register_field']);
    }

    /**
     * Register global Gutenberg Block field.
     *
     * @throws \Exception
     */
    public function register_field(): void
    {
        \register_graphql_field(
            'Theme',
            self::FIELD_NAME,
            [
                'type' => 'String',
                'description' => \__('The global stylesheets list.', 'statik-graphql'),
                'resolve' => static fn (Theme $theme): Deferred => new Deferred(
                    static function () use ($theme): string {
                        $fn = static fn (): string => $theme->slug;
                        /**
                         * Override default `stylesheet` to get data for specific theme.
                         */
                        \add_filter('stylesheet', $fn, 100);
                        \add_filter('template', $fn, 100);
                        \WP_Theme_JSON_Resolver::clean_cached_data();
                        $globalStylesheet = \wp_get_global_stylesheet();
                        \remove_filter('stylesheet', $fn, 100);
                        \remove_filter('template', $fn, 100);

                        return $globalStylesheet;
                    }
                ),
            ]
        );
    }
}
