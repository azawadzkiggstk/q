<?php

declare(strict_types=1);

namespace Statik\Blocks\CustomBlocks\Statik\UniversalBlock;

use Statik\Blocks\BlockType\AbstractBlockType;
use Statik\Blocks\BlockType\EditorInlineDataInterface;

/**
 * Class UniversalBlock.
 */
class UniversalBlockType extends AbstractBlockType implements EditorInlineDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEditorInlineData(): array
    {
        /**
         * Fire Universal Blocks editor options.
         *
         * @param array $rawData available editor options
         *
         * @returns array
         */
        $rawData = (array) \apply_filters(
            'Statik\Blocks\UniversalBlock\editorOptions',
            ['Table' => ['Default table'], 'Chart' => ['Default chart'], 'Pricing' => ['Default pricing']]
        );

        foreach ($rawData as $optionKey => $extras) {
            if (\is_numeric($optionKey)) {
                continue;
            }

            $field = [
                'slug' => \lcfirst(\str_replace('-', '', \ucwords(\sanitize_title($optionKey), '-'))),
                'name' => $optionKey,
                'extra' => [],
            ];

            foreach ($extras as $extra) {
                if (\is_numeric($extra)) {
                    continue;
                }

                $field['extra'][] = [
                    'slug' => \lcfirst(\str_replace('-', '', \ucwords(\sanitize_title($extra), '-'))),
                    'name' => $extra,
                ];
            }

            $data[] = $field;
        }

        return $data ?? [];
    }
}
