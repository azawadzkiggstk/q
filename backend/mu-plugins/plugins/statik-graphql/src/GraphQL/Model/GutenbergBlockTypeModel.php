<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Model;

use Statik\Blocks\BlockType\BlockTypeInterface;
use WPGraphQL\Model\Model;

/**
 * Class GutenbergBlockTypeModel.
 */
class GutenbergBlockTypeModel extends Model
{
    /** @var \WP_Block_Type */
    protected $data;

    /**
     * GutenbergBlockModel constructor.
     *
     * @throws \Exception
     */
    public function __construct(BlockTypeInterface $data, private string $key)
    {
        $this->data = $data;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        if (false === empty($this->fields)) {
            return;
        }

        $wpBlockType = $this->data->getWpBlockType();

        $this->fields = [
            'id' => fn (): string => $this->key,
            'databaseId' => fn (): string => $wpBlockType->name,
            'name' => fn (): string => $wpBlockType->name,
            'parent' => fn (): ?string => $wpBlockType->parent[0] ?? null,
            'title' => fn (): string => $wpBlockType->title,
            'description' => fn (): string => $wpBlockType->description,
            'supports' => fn (): ?string => $this->toJson($wpBlockType->supports),
            'attributes' => fn (): ?string => $this->toJson($wpBlockType->attributes),
            'usesContext' => fn (): ?string => $this->toJson($wpBlockType->uses_context),
            'apiVersion' => fn (): int => $wpBlockType->api_version,
            'raw' => fn (): ?string => $this->toJson($wpBlockType),
        ];
    }

    /**
     * Parse array or object to JSON.
     */
    private function toJson(mixed $data): ?string
    {
        if (empty($data)) {
            return null;
        }

        $json = \json_encode($data);

        if (\JSON_ERROR_NONE !== \json_last_error()) {
            return null;
        }

        return $json;
    }
}
