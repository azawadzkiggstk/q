<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Data\Connection;

use GraphQLRelay\Connection\ArrayConnection;
use Statik\Blocks\Block\Block;
use Statik\Blocks\DI as BlocksDI;
use Statik\GraphQL\GraphQL\Data\Loader\GutenbergBlocksLoader;
use Statik\GraphQL\GraphQL\Utils\Relay;
use WPGraphQL\Data\Connection\AbstractConnectionResolver;

/**
 * Class GutenbergBlockConnectionResolver.
 */
class GutenbergBlockConnectionResolver extends AbstractConnectionResolver
{
    /**
     * {@inheritdoc}
     */
    public function get_loader_name(): string
    {
        return GutenbergBlocksLoader::LOADER_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function get_query_args(): array
    {
        return $this->query_args ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function get_query(): array
    {
        $query = [];

        $blocks = BlocksDI::PostManager()->create($this->query_args['post_id']);

        foreach ($blocks as $block) {
            /** @var Block $block */
            $blockId = Relay::toBlockGlobalId((string) $this->query_args['post_id'], (string) $block->id);
            $query[$blockId] = $block;
        }

        if (false === empty($this->args['after'])) {
            $offset = \substr(\base64_decode($this->args['after']), \strlen(ArrayConnection::PREFIX));
            $offset = \array_search($offset, \array_keys($query));

            $query = \array_slice($query, $offset);
        }

        if (false === empty($this->args['before'])) {
            $offset = \substr(\base64_decode($this->args['before']), \strlen(ArrayConnection::PREFIX));
            $offset = \array_search($offset, \array_keys($query));

            $query = \array_slice($query, 0, $offset);
        }

        if (isset($this->args['last']) && \is_numeric($this->args['last'])) {
            $query = \array_reverse($query);
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    public function get_offset(): string
    {
        if (false === empty($this->args['after'])) {
            return \substr(\base64_decode($this->args['after']), \strlen(ArrayConnection::PREFIX));
        }
        if (false === empty($this->args['before'])) {
            return \substr(\base64_decode($this->args['before']), \strlen(ArrayConnection::PREFIX));
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function get_ids(): array
    {
        return \array_keys($this->query);
    }

    /**
     * {@inheritdoc}
     */
    public function should_execute(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function is_valid_offset($offset): bool
    {
        return isset($this->query[$offset]);
    }
}
