<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Type\Field;

use Statik\GraphQL\GraphQL\Type\Interface\GutenbergBlockInterface;
use Statik\GraphQL\GraphQL\Utils\Hookable;

/**
 * Class GutenbergBlockField.
 */
class GutenbergBlockField implements Hookable
{
    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerType']);
    }

    /**
     * Register object type for Statik Gutenberg Block.
     */
    public function registerType(): void
    {
        $registry = \WP_Block_Type_Registry::get_instance();

        foreach ($registry->get_all_registered() as $blockTypeName => $blockType) {
            $nameSlug = \str_replace(['/', '-'], '', \ucwords($blockTypeName, '/-'));

            \register_graphql_object_type(
                "{$nameSlug}Block",
                [
                    'description' => \sprintf(\__('The single %s block.', 'statik-graphql'), $blockTypeName),
                    'interfaces' => [GutenbergBlockInterface::INTERFACE_NAME],
                    'eagerlyLoadType' => true,
                    'fields' => [],
                ]
            );
        }
    }
}
