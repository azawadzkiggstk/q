<?php

declare(strict_types=1);

namespace Statik\Luna\GravityForms\Properties;

\defined('ABSPATH') || exit('Direct access is not permitted!');

use GF_Field;
use WPGraphQL\GF\Type\WPInterface\FormField;

/**
 * Class DisabledProperty.
 */
class DisabledProperty
{
    /**
     * DisabledProperty constructor.
     */
    public function __construct()
    {
        \add_filter('Statik\GraphQL\registerTypeConfig', [$this, 'addGraphQlProperty'], 10, 2);

        if ('gf_edit_forms' === ($_GET['page'] ?? false)) {
            \add_action('gform_field_appearance_settings', [$this, 'createSettingField']);
            \add_filter('admin_footer', [$this, 'enqueueAssets']);
            \add_filter('gform_tooltips', [$this, 'addCustomTooltip']);
        }
    }

    /**
     * Enqueue javascript that is required to make the field working correctly.
     */
    public function enqueueAssets(): void
    {
        ?>
        <script>
          (() => gform.addAction(
            'gform_post_load_field_settings',
            ([field]) => document.getElementById('field_statik_disabled').checked = field.disabled || false
          ))()
        </script>
        <?php
    }

    /**
     * Add settings field in the Gravity Forms form editor screen.
     */
    public function createSettingField(int $position): void
    {
        if (500 === $position) { ?>
            <li class="statik_disabled_setting field_setting">
                <input type="checkbox" id="field_statik_disabled"
                       onclick="SetFieldProperty('disabled', this.checked);"/>
                <label for="field_statik_disabled" class="section_label">
                    <?= \__('Disabled', 'statik-luna'); ?>
                    <?= \gform_tooltip('form_field_statik_disabled', true); ?>
                </label>
            </li>
        <?php }
    }

    /**
     * Register custom tooltips that are required for the field.
     */
    public function addCustomTooltip(array $tooltips): array
    {
        $tooltips['form_field_statik_disabled'] = \sprintf(
            '<strong>%s</strong>%s',
            \__('Enable Disabled', 'statik-luna'),
            \__('Make the field disabled and disallow modify the value.', 'statik-luna')
        );

        return $tooltips;
    }

    /**
     * Register new field in Statik GraphQL plugin.
     */
    public function addGraphQlProperty(mixed $config, string $typeName): mixed
    {
        if ($typeName !== FormField::$type || false === \is_array($config)) {
            return $config;
        }

        $config['fields']['disabled'] = [
            'type' => 'Boolean',
            'description' => \__('Determines if the field is disabled.', 'statik-luna'),
            'resolve' => static fn (GF_Field $field): bool => (bool) $field['disabled'],
        ];

        return $config;
    }
}
