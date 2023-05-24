<?php

declare(strict_types=1);

namespace Statik\Deploy\Utils;

/**
 * Class User.
 */
class User
{
    /**
     * Resolve the user name.
     */
    public static function resolve(mixed $user, bool $withTooltip = false): string
    {
        if ($user instanceof \WP_User) {
            $data = [$user->display_name, ''];
        } elseif ('automatic@statik.space' === $user) {
            $data = [
                \__('[Automatic deployment]', 'statik-deployment'),
                Tooltip::add(\__(
                    'The deployment was triggered automatically by a CRON task. These actions can be edited in the '
                    . 'Deployment Settings.',
                    'statik-deployment'
                )),
            ];
        } elseif (false === empty($user)) {
            $data = [
                $user,
                Tooltip::add(\__(
                    'The user has been removed or the deployment was made from the Statik Dashboard and the email '
                    . 'address cannot be recognised.',
                    'statik-deployment'
                )),
            ];
        } else {
            $data = [
                \__('[Unknown user]', 'statik-deployment'),
                Tooltip::add(\__(
                    'The user cannot be recognised in this WordPress instance. Log in to the Statik Dashboard to see '
                    . 'more details.',
                    'statik-deployment'
                )),
            ];
        }

        return \sprintf('%s%s', $data[0], $withTooltip ? " {$data[1]}" : null);
    }
}
