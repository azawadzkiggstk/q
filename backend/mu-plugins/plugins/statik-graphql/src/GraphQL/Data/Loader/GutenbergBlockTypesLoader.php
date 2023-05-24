<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Data\Loader;

use GraphQL\Deferred;
use Statik\Blocks\DI;
use Statik\GraphQL\GraphQL\Model\GutenbergBlockTypeModel;
use Statik\GraphQL\GraphQL\Utils\Relay;
use WPGraphQL\Data\Loader\AbstractDataLoader;

/**
 * Class GutenbergBlockTypesLoader.
 */
class GutenbergBlockTypesLoader extends AbstractDataLoader
{
    public const LOADER_NAME = 'GutenbergBlockTypesLoader';

    /**
     * {@inheritdoc}
     */
    protected function loadKeys(array $keys): array
    {
        $blockTypesManager = DI::BlocksManager();

        foreach ($keys as $key) {
            ['id' => $blockId] = Relay::fromGlobalId((string) $key);

            if (false === isset($blockTypes[$blockId])) {
                $blockTypes[$blockId] = $blockTypesManager->getBlockType($blockId);
            }

            $loaded[$key] = $blockTypes[$blockId];
        }

        return $loaded ?? [];
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    protected function get_model($entry, $key): GutenbergBlockTypeModel
    {
        return new GutenbergBlockTypeModel($entry, $key);
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
