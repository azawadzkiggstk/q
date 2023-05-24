<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Model;

use Statik\Blocks\Block\Block;
use Statik\Blocks\BlockType\GraphQlExtraAttributesInterface;
use Statik\Blocks\BlockType\SettingsInterface;
use Statik\GraphQL\GraphQL\Utils\Relay;
use Statik\GraphQL\GraphQL\Utils\Text;
use WPGraphQL\Model\Model;

/**
 * Class GutenbergBlockModel.
 *
 * @property string      $id
 * @property string      $databaseId
 * @property string|null $parentId
 * @property string|null $parentDatabaseId
 * @property string      $postId
 * @property string      $postDatabaseId
 * @property string      $name
 * @property string|null $rawHtml
 * @property array       $attributes
 * @property array|null  $postsIds
 * @property int|null    $teamMemberId
 * @property int|null    $mediaItemId
 */
class GutenbergBlockModel extends Model
{
    /**
     * @var Block
     */
    protected $data;

    /**
     * GutenbergBlockModel constructor.
     *
     * @throws \Exception
     */
    public function __construct(Block $data, private string $key)
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

        $wpPostId = $this->data->getContext('post.ID');

        $this->fields = [
            'id' => fn (): string => $this->key,
            'databaseId' => fn (): int => $this->data->id,
            'parentId' => fn (): ?string => null === $this->data->parentId
                ? null
                : Relay::toBlockGlobalId((string) $wpPostId, (string) $this->data->parentId),
            'parentDatabaseId' => fn (): ?int => null === $this->data->parentId ? null : (int) $this->data->parentId,
            'postId' => fn (): string => Relay::toGlobalId('post', $wpPostId),
            'postDatabaseId' => fn (): int => $wpPostId ?: null,
            'name' => fn (): string => $this->data->name,
            'rawHtml' => fn (): string => $this->getRendered(),
            'attributes' => fn (): array => $this->getAttributes(),
            'block' => fn (): string => (string) \json_encode((array) $this->data),
        ];

        /**
         * Fire block fields filter.
         *
         * @param array $fields block fields
         * @param self  $this   block model instance
         *
         * @return array
         */
        $this->fields = (array) \apply_filters('Statik\GraphQl\blockFields', $this->fields, $this);
    }

    /**
     * Prepare Block Attributes for GraphQL schema.
     */
    private function getAttributes(): array
    {
        $attributes = $this->data->getAttributes();
        $blockType = $this->data->getBlockType();

        if ($blockType instanceof SettingsInterface) {
            $attributes = \array_merge($attributes, $blockType->getSettings());
        }

        if ($blockType instanceof GraphQlExtraAttributesInterface) {
            $attributes = \array_merge($attributes, $blockType->getGraphQlExtraAttributes());
        }

        foreach ($attributes as $attribute => $value) {
            $value = \is_callable($value) ? \call_user_func($value, $this->data) : $value;

            $graphQlAttributes[$attribute] = [
                'name' => $attribute,
                'value' => match (true) {
                    \is_array($value) => \json_encode($value),
                    default => Text::toUtf8($value),
                },
            ];
        }

        return $graphQlAttributes ?? [];
    }

    /**
     * Get the rendered version of the block.
     */
    private function getRendered(): string
    {
        $content = $this->data->render();
        $content = \wptexturize($content);
        $content = \convert_smilies($content);
        $content = \shortcode_unautop($content);
        $content = \wp_filter_content_tags($content);
        $content = \do_shortcode($content);

        return \str_replace(']]>', ']]&gt;', $content);
    }
}
