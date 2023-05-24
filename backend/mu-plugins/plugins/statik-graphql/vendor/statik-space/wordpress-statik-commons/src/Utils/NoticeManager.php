<?php

declare(strict_types=1);

namespace Statik\GraphQL\Common\Utils;

/**
 * Class NoticeManager.
 */
class NoticeManager
{
    private const VALID_TYPES = ['error', 'warning', 'success', 'info'];

    private const META_NAME = 'statik_notice';

    /**
     * Get sanitized meta name.
     */
    private static function getMetaName(): string
    {
        return self::META_NAME . '_' . \sanitize_title(__NAMESPACE__);
    }

    /**
     * Get rendered (HTML) notices for current user.
     */
    public static function render(bool $flush = true): ?string
    {
        $notices = '';

        foreach (self::toArray() as $notice) {
            $notices .= \sprintf(
                '<div class="notice %1$s"><p>%2$s</p></div>',
                $notice['c'],
                \sprintf($notice['m'], ...(array) $notice['a'])
            );
        }

        $flush && self::flush();

        return $notices;
    }

    /**
     * Display rendered notices for current user.
     */
    public static function display(): void
    {
        echo self::render();
    }

    /**
     * Display rendered notices for current user.
     */
    public static function flush(): void
    {
        $function = static function (): void {
            if (false === \is_user_logged_in()) {
                return;
            }

            \delete_user_meta(\get_current_user_id(), self::getMetaName());
        };

        false === \function_exists('wp_get_current_user') ? \add_action('plugins_loaded', $function) : $function();
    }

    /**
     * Get notices for current user.
     */
    private static function toArray(): array
    {
        $notices = \get_user_meta(\get_current_user_id(), self::getMetaName(), true);

        return \array_values($notices ?: []);
    }

    /**
     * Add admin error notice for current user.
     */
    public static function error(string $message, array $args = [], bool $dismissible = true): void
    {
        self::register('error', $message, $args, $dismissible);
    }

    /**
     * Add admin warning notice for current user.
     */
    public static function warning(string $message, array $args = [], bool $dismissible = true): void
    {
        self::register('warning', $message, $args, $dismissible);
    }

    /**
     * Add admin success notice for current user.
     */
    public static function success(string $message, array $args = [], bool $dismissible = true): void
    {
        self::register('success', $message, $args, $dismissible);
    }

    /**
     * Add admin info notice for current user.
     */
    public static function info(string $message, array $args = [], bool $dismissible = true): void
    {
        self::register('info', $message, $args, $dismissible);
    }

    /**
     * Add admin notice for current user.
     */
    private static function register(string $class, string $message, array $args = [], bool $dismissible = true): void
    {
        if (empty($message) || false === \in_array($class, self::VALID_TYPES, true)) {
            return;
        }

        $class = 'notice-' . $class;

        if ($dismissible) {
            $class .= ' is-dismissible';
        }

        $function = static function () use ($class, $message, $args): void {
            if (\wp_doing_cron() || \wp_doing_ajax() || false === \is_user_logged_in()) {
                return;
            }

            $id = \get_current_user_id();
            $meta = \get_user_meta($id, self::getMetaName(), true) ?: [];
            $meta[\md5($class . $message)] = ['c' => $class, 'm' => $message, 'a' => $args];

            \update_user_meta($id, self::getMetaName(), $meta);
        };

        false === \function_exists('wp_get_current_user') ? \add_action('plugins_loaded', $function) : $function();
    }
}
