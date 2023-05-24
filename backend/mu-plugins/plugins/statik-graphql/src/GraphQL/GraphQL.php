<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL;

use Statik\GraphQL\DI;
use Statik\GraphQL\GraphQL\Connection\ContentNodeToGutenbergBlocksConnection;
use Statik\GraphQL\GraphQL\Connection\ContentNodeToSeoConnection;
use Statik\GraphQL\GraphQL\Connection\GlobalSettingsToThemeConnection;
use Statik\GraphQL\GraphQL\Connection\GutenbergBlockToContentNodeConnection;
use Statik\GraphQL\GraphQL\Connection\GutenbergBlockToExtraFieldsConnection;
use Statik\GraphQL\GraphQL\Connection\GutenbergFormsBlockToFormConnection;
use Statik\GraphQL\GraphQL\Connection\MenuItemToMegamenuConnection;
use Statik\GraphQL\GraphQL\Connection\RootToGutenbergBlockTypesConnection;
use Statik\GraphQL\GraphQL\Connection\TermNodeToSeoConnection;
use Statik\GraphQL\GraphQL\Data\Loader\LoaderRegister;
use Statik\GraphQL\GraphQL\Field\ContentNodeDateFormatField;
use Statik\GraphQL\GraphQL\Field\ContentNodeReadTimeField;
use Statik\GraphQL\GraphQL\Field\ContentNodeSharingHreflangField;
use Statik\GraphQL\GraphQL\Field\GutenbergBlockTypeField as RootToGutenbergBlockTypeField;
use Statik\GraphQL\GraphQL\Field\MediaItemPlaceholderBase64Field;
use Statik\GraphQL\GraphQL\Field\MenuIsHierarchicalField;
use Statik\GraphQL\GraphQL\Field\RootQueryTranslationField;
use Statik\GraphQL\GraphQL\Field\ThemeGlobalStylesheetsField;
use Statik\GraphQL\GraphQL\Filters\ExecutionResponse;
use Statik\GraphQL\GraphQL\Filters\TypeRegistry;
use Statik\GraphQL\GraphQL\Type\Enum\ContentTypeDateFormatEnum;
use Statik\GraphQL\GraphQL\Type\Field\GutenbergBlockAttributesField;
use Statik\GraphQL\GraphQL\Type\Field\GutenbergBlockField;
use Statik\GraphQL\GraphQL\Type\Field\GutenbergBlockTypeField;
use Statik\GraphQL\GraphQL\Type\Field\NodeSeoField;
use Statik\GraphQL\GraphQL\Type\Field\SharingHreflangField;
use Statik\GraphQL\GraphQL\Type\Field\ThemeGlobalSettingsField;
use Statik\GraphQL\GraphQL\Type\Interface\GutenbergBlockInterface;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\ACF\ACF;
use WPGraphQL\GF\GF;

/**
 * Class GraphQL.
 */
class GraphQL
{
    private array $routes = [];
    private array $filters = [];

    /**
     * Run all GraphQL things.
     */
    public function run(): self
    {
        if (false === \class_exists('WPGraphQL')) {
            require_once DI::dir('/vendor/wp-graphql/wp-graphql/src/WPGraphQL.php');
        }

        new Admin\Admin();

        \WPGraphQL::instance();

        $this->registerIntegrations();

        $this->registerRoutes();
        $this->callHookableRoutes();

        $this->registerFilters();
        $this->callHookableFilters();

        return $this;
    }

    /**
     * Get routes.
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Get route.
     */
    public function getRoute(string $key)
    {
        return $this->routes[$key];
    }

    /**
     * Set route from class name.
     */
    public function setRoute(string $className): self
    {
        $this->routes[$className] = new $className();

        return $this;
    }

    /**
     * Get filters.
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Get filter.
     */
    public function getFilter(string $key)
    {
        return $this->filters[$key];
    }

    /**
     * Set filter from class name.
     */
    public function setFilter(string $className): self
    {
        $this->filters[$className] = new $className();

        return $this;
    }

    /**
     * Register integrations.
     */
    private function registerIntegrations(): void
    {
        if (Integration::isAcfPlugin()) {
            ACF::instance();
        }

        if (Integration::isGravityFormsPlugin()) {
            \define(
                'WPGRAPHQL_GF_PLUGIN_FILE',
                DI::dir('vendor/harness-software/wp-graphql-gravity-forms/wp-graphql-gravity-forms.php')
            );

            GF::instance();
        }
    }

    /**
     * Register routes.
     */
    private function registerRoutes(): void
    {
        // Register enum types
        $this->setRoute(ContentTypeDateFormatEnum::class);

        // Register field types
        $this->setRoute(ThemeGlobalSettingsField::class);

        // Register global fields
        $this->setRoute(RootQueryTranslationField::class);
        $this->setRoute(MediaItemPlaceholderBase64Field::class);
        $this->setRoute(ContentNodeDateFormatField::class);
        $this->setRoute(ContentNodeReadTimeField::class);
        $this->setRoute(ThemeGlobalStylesheetsField::class);

        // Register global connections
        $this->setRoute(GlobalSettingsToThemeConnection::class);

        if (Integration::isStatikGutenbergBlocksPlugin()) {
            // Register loaders
            $this->setRoute(LoaderRegister::class);

            // Register interface types
            $this->setRoute(GutenbergBlockInterface::class);

            // Register field types
            $this->setRoute(GutenbergBlockField::class);
            $this->setRoute(GutenbergBlockAttributesField::class);
            $this->setRoute(GutenbergBlockTypeField::class);
            $this->setRoute(RootToGutenbergBlockTypeField::class);

            // Register connections
            $this->setRoute(ContentNodeToGutenbergBlocksConnection::class);
            $this->setRoute(GutenbergBlockToContentNodeConnection::class);
            $this->setRoute(GutenbergBlockToExtraFieldsConnection::class);
            $this->setRoute(RootToGutenbergBlockTypesConnection::class);
        }

        if (Integration::isStatikGutenbergBlocksPlugin() && Integration::isGravityFormsPlugin()) {
            // Register connections
            $this->setRoute(GutenbergFormsBlockToFormConnection::class);
        }

        if (Integration::isStatikMenuPlugin()) {
            // Register fields
            $this->setRoute(MenuIsHierarchicalField::class);

            // Register connections
            $this->setRoute(MenuItemToMegamenuConnection::class);
        }

        if (Integration::isTheSeoFrameworkPlugin()) {
            // Register field types
            $this->setRoute(NodeSeoField::class);

            // Register connections
            $this->setRoute(ContentNodeToSeoConnection::class);
            $this->setRoute(TermNodeToSeoConnection::class);
        }

        if (Integration::isStatikSharingPlugin()) {
            // Register field types
            $this->setRoute(SharingHreflangField::class);

            // Register fields
            $this->setRoute(ContentNodeSharingHreflangField::class);
        }

        /**
         * Fire register routes filter.
         *
         * @param array $routes registered routes
         *
         * @return array
         */
        $this->routes = (array) \apply_filters('Statik\GraphQL\registerRoutes', $this->routes);
    }

    /**
     * Register routes actions and filters.
     */
    private function callHookableRoutes(): void
    {
        $routes = \array_filter($this->routes, static fn ($route) => $route instanceof Hookable);

        foreach ($routes as $route) {
            /** @var Hookable $route */
            $route->registerHooks();
        }
    }

    /**
     * Register custom filters.
     */
    private function registerFilters(): void
    {
        $this->setFilter(TypeRegistry::class);
        $this->setFilter(ExecutionResponse::class);
    }

    /**
     * Register filters actions and filters.
     */
    private function callHookableFilters(): void
    {
        $filters = \array_filter($this->filters, static fn ($route) => $route instanceof Hookable);

        foreach ($filters as $filter) {
            /** @var Hookable $filter */
            $filter->registerHooks();
        }
    }
}
