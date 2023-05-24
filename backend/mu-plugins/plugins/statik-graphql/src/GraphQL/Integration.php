<?php

declare(strict_types=1);

namespace Statik\GraphQL\GraphQL;

/**
 * Class Integration.
 */
final class Integration
{
    /**
     * Determinate if Statik Menu REST API plugin is enabled.
     */
    public static function isStatikMenuPlugin(): bool
    {
        return \class_exists('Statik\Menu\Plugin');
    }

    /**
     * Determinate if Statik Gutenberg Blocks plugin is enabled.
     */
    public static function isStatikGutenbergBlocksPlugin(): bool
    {
        return \class_exists('Statik\Blocks\Plugin');
    }

    /**
     * Determinate if Statik Sharing plugin is enabled.
     */
    public static function isStatikSharingPlugin(): bool
    {
        return \class_exists('Statik\Sharing\Plugin');
    }

    /**
     * Determinate if Gravity Forms plugin is enabled.
     */
    public static function isGravityFormsPlugin(): bool
    {
        return \class_exists('GFForms');
    }

    /**
     * Determinate if The SEO Framework plugin is enabled.
     */
    public static function isTheSeoFrameworkPlugin(): bool
    {
        return \defined('THE_SEO_FRAMEWORK_VERSION');
    }

    /**
     * Determinate if Advanced Custom Fields plugin is enabled.
     */
    public static function isAcfPlugin(): bool
    {
        return \class_exists('ACF');
    }
}
