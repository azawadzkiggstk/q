<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Utils;

use GraphQLRelay\Node\Node;

/**
 * Class Relay.
 */
class Relay extends \GraphQLRelay\Relay
{
    /**
     * Takes a type name and an ID specific to that type name, and returns a
     * "global ID" that is unique among all types.
     */
    public static function toBlockGlobalId(string $parentId, string $id): string
    {
        return Node::toGlobalId('block', "{$parentId}:{$id}");
    }

    /**
     * Takes the "global ID" created by self::toGlobalId, and returns the type name and ID
     * used to create it.
     */
    public static function fromBlockGlobalId(string $globalId): array
    {
        $type = Node::fromGlobalId($globalId);

        return \explode(':', $type['id']);
    }
}
