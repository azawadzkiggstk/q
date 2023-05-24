<?php

declare(strict_types=1);

namespace Statik\Deploy\Utils;

use Statik\Deploy\CronManager;

/**
 * Class AppPass.
 */
class AppPass
{
    private const APP_ID = '1b8c6048-a68c-4a20-9028-4e8a0045124d';

    /**
     * Generate application password.
     */
    public static function generateCredentials(\WP_User $user = null, bool $scheduleFlush = true): ?array
    {
        if (null === $user) {
            $user = \wp_get_current_user();
        }

        if (false === \wp_is_application_passwords_available_for_user($user)) {
            return null;
        }

        $pass = \WP_Application_Passwords::create_new_application_password(
            $user->ID,
            ['name' => \sprintf('Statik Deployment - %s', \wp_generate_uuid4()), 'app_id' => self::APP_ID]
        );

        if (\is_wp_error($pass)) {
            return null;
        }

        $scheduleFlush && \wp_schedule_single_event(
            \time() + 30 * MINUTE_IN_SECONDS,
            CronManager::CRON_APP_PASS_JOB_NAME,
            [$user->ID, $pass[1]['uuid']]
        );

        return ['user' => $user->user_login, 'pass' => $pass[0]];
    }

    /**
     * Flush application passwords after 10 minutes of inactivity.
     */
    public static function flushCredentials(\WP_User $user = null): bool
    {
        if (null === $user) {
            $user = \wp_get_current_user();
        }

        if (false === \wp_is_application_passwords_available_for_user($user)) {
            return false;
        }

        foreach (\WP_Application_Passwords::get_user_application_passwords($user->ID) as $password) {
            if (self::APP_ID !== $password['app_id']) {
                continue;
            }

            $lastUsed = $password['last_used'] ?: $password['created'];

            if ($lastUsed + 10 * MINUTE_IN_SECONDS > \time()) {
                continue;
            }

            \WP_Application_Passwords::delete_application_password($user->ID, $password['uuid']);
        }

        return true;
    }
}
