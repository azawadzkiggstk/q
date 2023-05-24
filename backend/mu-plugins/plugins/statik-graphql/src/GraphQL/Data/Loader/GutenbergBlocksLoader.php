<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Data\Loader;

use GraphQL\Deferred;
use Statik\Blocks\Block\Block;
use Statik\Blocks\DI as BlocksDI;
use Statik\GraphQL\GraphQL\Model\GutenbergBlockModel;
use Statik\GraphQL\GraphQL\Utils\Relay;
use WPGraphQL\Data\Loader\AbstractDataLoader;

/**
 * Class GutenbergBlocksLoader.
 */
class GutenbergBlocksLoader extends AbstractDataLoader
{
    public const LOADER_NAME = 'GutenbergBlocksLoader';

    /**
     * {@inheritdoc}
     */
    protected function loadKeys(array $keys): array
    {
        foreach ($keys as $key) {
            [$postId, $blockId] = Relay::fromBlockGlobalId((string) $key);

            if (false === isset($blocks[$postId])) {
                $blocks[$postId] = BlocksDI::PostManager()->create((int) $postId);
            }

            $block = $blocks[$postId][$blockId] ?? null;
            $loaded[$key] = $block instanceof Block ? $block : null;
        }

        return $loaded ?? [];
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    protected function get_model($entry, $key): GutenbergBlockModel
    {
        return new GutenbergBlockModel($entry, $key);
    }

    /**
     * {@inheritDoc}
     */
    public function load_deferred($database_id): ?Deferred
    {
        if (empty($database_id)) {
            return null;
        }

        $database_id = \sanitize_text_field($database_id);

        $this->buffer([$database_id]);

        return new Deferred(fn () => $this->load($database_id));
    }
}
