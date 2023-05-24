<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL\Utils;

/**
 * Class Media.
 */
class Media
{
    private const PLACEHOLDER_META_KEY = 'placeholderBase64';

    /**
     * Get media image encoded to Base64.
     */
    public static function getMediaBase64Placeholder(int $mediaId): ?string
    {
        $meta = \get_post_meta($mediaId, self::PLACEHOLDER_META_KEY, true);

        if (false === empty($meta)) {
            return (string) $meta;
        }

        $imagePath = \wp_get_original_image_path($mediaId);
        $imageEditor = \wp_get_image_editor($imagePath);

        if (\is_wp_error($imageEditor)) {
            return null;
        }

        $imageEditor->resize(20, 20);
        $image = $imageEditor->save();

        if (\is_wp_error($image)) {
            return null;
        }

        $imageType = \pathinfo((string) $image['path'], \PATHINFO_EXTENSION);
        $imageData = \file_get_contents((string) $image['path']);
        $imageBase64 = \base64_encode((string) $imageData);
        \wp_delete_file($image['path']);

        if ($imageBase64) {
            $imageBase64 = "data:image/{$imageType};base64,{$imageBase64}";
            \update_post_meta($mediaId, self::PLACEHOLDER_META_KEY, $imageBase64);
        }

        return $imageBase64 ?: null;
    }
}
