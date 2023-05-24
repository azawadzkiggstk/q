<?php

declare(strict_types=1);

namespace Statik\Blocks\Utils\Acf;

use Illuminate\Support\Str;

/**
 * AcfFields class.
 */
class AcfFields
{
    /**
     * Get ACF fields names.
     */
    public static function getFieldsNames(): array
    {
        if (false === \function_exists('acf_get_field_groups')) {
            return [];
        }

        static $cache;

        $cache ??= self::prepareFieldsNames();

        return $cache;
    }

    /**
     * Prepare ACF fields names.
     */
    private static function prepareFieldsNames(): array
    {
        $postTypes = \get_post_types(['show_in_rest' => true]);

        $acfLocation = new AcfLocation([], $postTypes);
        $acfLocation->determineLocationRules();

        $rules = $acfLocation->getRules();

        foreach ($postTypes as $postType) {
            $fieldsGroups = $rules[$postType] ?? [];

            foreach ($fieldsGroups as $fieldsGroup) {
                $preparedResults[$postType] = \array_merge(
                    $preparedResults[$postType] ?? [],
                    self::parseFields(\acf_get_fields($fieldsGroup))
                );
            }
        }

        return \array_filter($preparedResults ?? []);
    }

    /**
     * Parse ACF fields.
     */
    public static function parseFields(array $fields, string $parentKey = null): array
    {
        $prefix = $parentKey ? "{$parentKey}." : '';

        foreach ($fields as $field) {
            $key = Str::camel($prefix . $field['name']);

            if ('url' === $field['type']) {
                $preparedFields[$key] = $field['label'];

                continue;
            }

            if (false === \is_array($field['sub_fields'] ?? null) || 'repeater' === $field['type']) {
                continue;
            }

            $preparedFields = \array_merge($preparedFields ?? [], self::parseFields($field['sub_fields'], $key));
        }

        return $preparedFields ?? [];
    }
}
