<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Connection;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Statik\GraphQL\GraphQL\Model\GutenbergBlockModel;
use Statik\GraphQL\GraphQL\Utils\Hookable;
use WPGraphQL\AppContext;
use WPGraphQL\GF\Data\Connection\FormsConnectionResolver;
use WPGraphQL\GF\Type\WPObject\Form\Form;

/**
 * Class GutenbergFormsBlockToFormConnection.
 */
class GutenbergFormsBlockToFormConnection implements Hookable
{
    public const CONNECTION_FIELD_NAME = 'gravityForm';

    /**
     * {@inheritDoc}
     */
    public function registerHooks(): void
    {
        \add_action('graphql_register_types', [$this, 'registerConnection']);
    }

    /**
     * Register connections to Cards Gutenberg Block.
     *
     * @throws \Exception
     */
    public function registerConnection(): void
    {
        \register_graphql_connection([
            'fromType' => 'GravityformsFormBlock',
            'fromFieldName' => self::CONNECTION_FIELD_NAME,
            'toType' => Form::$type,
            'oneToOne' => true,
            'resolve' => static function (
                GutenbergBlockModel $block,
                array $args,
                AppContext $context,
                ResolveInfo $info
            ): ?Deferred {
                if (false === isset($block->attributes['formId'])) {
                    return null;
                }

                $resolver = new FormsConnectionResolver($block, $args, $context, $info);
                $resolver->one_to_one = true;
                $resolver->set_query_arg('form_ids', [$block->attributes['formId']['value']]);

                return $resolver->get_connection();
            },
        ]);
    }
}
