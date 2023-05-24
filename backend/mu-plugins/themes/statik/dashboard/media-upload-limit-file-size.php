<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_filter('wp_handle_upload_prefilter', 'statik_media_upload_limit_file_size');

\add_filter('gettext', 'statik_media_upload_replace_text', 10, 3);

if (false === \function_exists('statik_media_upload_limit_file_size')) {
    /**
     * Limit the max allowed file size for WordPress media upload based on the
     * file MINE type.
     */
    function statik_media_upload_limit_file_size(array $file): array
    {
        if (empty($file['type'])) {
            return $file;
        }

        [$typeFamily, $typeName] = \explode('/', $file['type']);
        $maxUploadSize = \wp_max_upload_size();

        $mediaUploadLimits = (array) \apply_filters('Statik\Luna\mediaUploadLimits', []);

        if (false === \array_key_exists($typeFamily, $mediaUploadLimits)) {
            return $file;
        }

        if (\is_array($mediaUploadLimits[$typeFamily])) {
            if (false === \array_key_exists($typeName, $mediaUploadLimits[$typeFamily])) {
                return $file;
            }

            $maxUploadSize = \min($maxUploadSize, $mediaUploadLimits[$typeFamily][$typeName]);
            $fileType = "{$typeFamily}/{$typeName}";
        } else {
            $maxUploadSize = \min($maxUploadSize, $mediaUploadLimits[$typeFamily]);
            $fileType = "{$typeFamily}/*";
        }

        if ($file['size'] <= $maxUploadSize) {
            return $file;
        }

        $file['error'] = \sprintf(
            'Maximum upload file size for format %s is %s',
            $fileType,
            \size_format($maxUploadSize)
        );

        return $file;
    }
}

if (false === \function_exists('statik_media_upload_replace_text')) {
    /**
     * Replace `Maximum upload file size` text based on new limits.
     */
    function statik_media_upload_replace_text(mixed $translation): mixed
    {
        if ('Maximum upload file size: %s.' !== $translation) {
            return $translation;
        }

        $mediaUploadLimits = (array) \apply_filters('Statik\Luna\mediaUploadLimits', []);

        if (empty($mediaUploadLimits)) {
            return $translation;
        }

        $maxUploadSize = \wp_max_upload_size();
        $translation = 'Maximum upload file size:<ul>';

        foreach ($mediaUploadLimits as $family => $types) {
            if (\is_array($types)) {
                foreach ($types as $type => $maxLimit) {
                    $translation .= \sprintf(
                        '<li><strong>%s</strong> - %s</li>',
                        \strtoupper($type),
                        \size_format(\min($maxLimit, $maxUploadSize))
                    );
                }
            } else {
                $translation .= \sprintf(
                    '<li><strong>%s</strong> - %s</li>',
                    \ucfirst($family),
                    \size_format(\min($types, $maxUploadSize))
                );
            }
        }

        $translation .= '<li><strong>Other file types</strong> - %s</li>';
        $translation .= '</ul>';

        \remove_filter('gettext', __FUNCTION__);

        return $translation;
    }
}
