<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Registry;

use WPGraphQL\Registry\TypeRegistry;

/**
 * Class StatikTypeRegistry.
 */
class StatikTypeRegistry extends TypeRegistry
{
    /**
     * {@inheritDoc}
     */
    public function register_connection($config): void
    {
        if ('RootQuery' === $config['fromType'] && false === \str_starts_with($config['fromFieldName'], 'all')) {
            $config['fromFieldName'] = 'all' . \ucfirst($config['fromFieldName']);
        }

        /**
         * Fire register connection config filter.
         *
         * @param object|array $config connection config
         *
         * @return object|array
         */
        $config = \apply_filters('Statik\GraphQL\registerConnectionConfig', $config);

        parent::register_connection($config);
    }

    /**
     * {@inheritDoc}
     */
    public function register_type(string $type_name, $config): void
    {
        /**
         * Fire register type config filter.
         *
         * @param object|array $config    type config
         * @param string       $type_name type name
         *
         * @return object|array
         */
        $config = \apply_filters('Statik\GraphQL\registerTypeConfig', $config, $type_name);

        parent::register_type($type_name, $config);
    }
}
