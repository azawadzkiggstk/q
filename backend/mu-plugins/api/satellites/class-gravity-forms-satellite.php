<?php

declare(strict_types=1);

namespace Statik\API\Satellites;

/**
 * Class GravityFormsSatellite.
 */
class GravityFormsSatellite extends AbstractSatellite
{
    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'STATIK_GRAVITY_CONSTANTS';
    }

    /**
     * {@inheritDoc}
     */
    public function initialize(?array $config): void
    {
        if (false === \class_exists('GFFormsModel')) {
            return;
        }

        if (false === \is_multisite()) {
            $this->setSingleSite($config);

            return;
        }

        foreach (\get_sites(['archived' => 0, 'spam' => 0, 'deleted' => 0]) as $site) {
            /** @var \WP_Site $site */
            \switch_to_blog($site->blog_id);
            $this->setSingleSite($config);
        }

        \restore_current_blog();
    }

    /**
     * Initialize config for single blog.
     */
    private function setSingleSite(?array $config): void
    {
        global $wpdb;

        \update_option(
            'gravityformsaddon_gravityformswebapi_settings',
            \array_merge(
                (array) \get_option('gravityformsaddon_gravityformswebapi_settings'),
                ['enabled' => 1]
            )
        );

        $wpdb->delete(\GFFormsModel::get_rest_api_keys_table_name(), ['description' => 'Statik API key']);
        $wpdb->insert(\GFFormsModel::get_rest_api_keys_table_name(), [
            'user_id' => \wp_get_current_user()->ID ?: 1,
            'description' => 'Statik API key',
            'permissions' => 'read_write',
            'consumer_key' => \GFWebAPI::api_hash($config['GRAVITY_FORMS_CONSUMER_KEY'] ?? ''),
            'consumer_secret' => $config['GRAVITY_FORMS_CONSUMER_SECRET'] ?? '',
            'truncated_key' => \substr($config['GRAVITY_FORMS_CONSUMER_KEY'] ?? '', -7),
        ]);
    }
}
