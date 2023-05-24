<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_action('Statik\Luna\reloadConfigSuccess', 'statik_register_managed_client_role', 1);

if (false === \function_exists('statik_register_managed_client_role')) {
    /**
     * Sets up a role for managed client. It disables all functionalities
     * that might potentially break the website.
     */
    function statik_register_managed_client_role(): void
    {
        \add_role('statik-managed-client', \__('Managed Client', 'statik-luna'));
        $role = \get_role('statik-managed-client');

        $ignoredCaps = [
            'customize',
            'update_themes',
            'update_plugins',
            'update_core',
            'switch_themes',
            'install_themes',
            'install_plugins',
            'import',
            'export',
            'edit_themes',
            'edit_plugins',
            'edit_files',
            'delete_themes',
            'delete_plugins',
            'activate_plugins',
            'promote_user',
        ];

        foreach (\get_role('administrator')->capabilities ?? [] as $cap => $grant) {
            if (\in_array($cap, $ignoredCaps, true)) {
                continue;
            }

            $role->add_cap($cap, $grant);
        }

        $role->add_cap('administrator');

        /** Custom Statik Roles */
        // General
        $role->add_cap('statik-custom-access');
        $role->add_cap('statik-access-edit-comments.php', false);
        $role->add_cap('statik-access-tools.php', false);
        $role->add_cap('statik-access-options-writing.php', false);
        $role->add_cap('statik-access-options-discussion.php', false);
        $role->add_cap('statik-access-options-media.php', false);
        $role->add_cap('statik-access-options-permalink.php', false);
        // ACF
        $role->add_cap('statik-access-acf-tools', false);
        $role->add_cap('statik-access-acf-settings-updates', false);
        $role->add_cap('statik-access-post-new.php?post_type=acf-field-group', false);
        $role->add_cap('statik-access-edit.php?post_type=acf-field-group', false);
        // SSO
        $role->add_cap('statik-access-onelogin_saml_configuration', false);
        // Statik
        $role->add_cap('statik-access-statik_settings', false);
        $role->add_cap('statik-access-graphiql-ide', false);
        // Gutenberg
        $role->add_cap('statik-access-gutenberg', false);
        // WP Mail SMTP
        $role->add_cap('statik-access-wp-mail-smtp', false);
    }
}
