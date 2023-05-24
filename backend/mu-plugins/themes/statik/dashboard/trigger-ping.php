<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

\add_action('admin_bar_menu', 'statik_add_trigger_ping_button', 100);
\add_action('init', 'statik_handle_trigger_ping_button');

if (false === \function_exists('statik_add_trigger_ping_button')) {
    /**
     * Add trigger ping button to admin bar for super admins.
     */
    function statik_add_trigger_ping_button(WP_Admin_Bar $admin_bar): void
    {
        if (false === \is_super_admin()) {
            return;
        }

        $message = \__(
            'Are you sure do you want to reload the Statik configuration? This action cannot be undone.',
            'statik'
        );

        $admin_bar->add_menu([
            'id' => 'statik-trigger-ping',
            'title' => '<span class="ab-icon dashicons dashicons-download" style="top:3px;"></span>'
                . \__('Reload Statik config', 'statik-luna'),
            'href' => \add_query_arg(['statik-trigger' => \wp_create_nonce('statik-reload')]),
            'meta' => ['onclick' => "return confirm(\"{$message}\");"],
        ]);
    }
}

if (false === \function_exists('statik_handle_trigger_ping_button')) {
    /**
     * Handle trigger ping button.
     */
    function statik_handle_trigger_ping_button(): void
    {
        if (false === isset($_GET['statik-trigger'])) {
            return;
        }

        $nonce = \filter_input(\INPUT_GET, 'statik-trigger');

        if ('success' === $nonce) {
            \add_action('admin_notices', static fn () => \printf(
                '<div class="notice notice-success is-dismissible"><p>%s</p><script>%s</script></div>',
                \__('Statik infrastructure configuration has been reloaded successfully!', 'statik-luna'),
                \sprintf('window.history.replaceState({}, document.title, "%s")', \remove_query_arg('statik-trigger'))
            ));

            return;
        }

        if (false === \wp_verify_nonce($nonce, 'statik-reload')) {
            return;
        }

        $request = new \WP_REST_Request('POST', '/statik-luna/v1/ping');
        $request->set_param('wait', 1);
        $response = \rest_do_request($request);

        if (200 !== $response->get_status()) {
            $response = $response->get_data();

            /**
             * Fire reload Statik config error action.
             *
             * @param string|array $response API response
             */
            \do_action('Statik\Luna\reloadConfigError', $response);

            \wp_die(
                \is_array($response)
                    ? $response['message'] ?? 'There has been a critical error on this website.'
                    : $response,
                '',
                ['back_link' => true]
            );
        }

        /**
         * Fire reload Statik config action.
         */
        \do_action('Statik\Luna\reloadConfigSuccess');

        \wp_redirect(\add_query_arg('statik-trigger', 'success'));
        exit;
    }
}
