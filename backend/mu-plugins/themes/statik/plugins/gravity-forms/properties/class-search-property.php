<?php

declare(strict_types=1);

namespace Statik\Luna\GravityForms\Properties;

\defined('ABSPATH') || exit('Direct access is not permitted!');

use GF_Field;
use WPGraphQLGravityForms\Types\Field\MultiSelectField;

/**
 * Class SearchProperty.
 */
class SearchProperty
{
    /**
     * SearchProperty constructor.
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
          (() => {
            const enhancedUi = document.getElementById('gfield_enable_enhanced_ui');
            const enableSearch = document.getElementById('field_statik_enableSearch');

            enhancedUi.onchange = () => {
              enableSearch.disabled = !enhancedUi.checked
              enableSearch.checked = !enhancedUi.checked && enableSearch.checked ? false : enableSearch.checked
              SetFieldProperty('enableSearch', enableSearch.checked);
            };

            gform.addAction(
              'gform_post_load_field_settings',
              ([field]) => {
                enableSearch.checked = field.search || false
                enableSearch.disabled = !(field.enableEnhancedUI || true)
              }
            )
          })()
        </script>
        <?php
    }

    /**
     * Add settings field in the Gravity Forms form editor screen.
     */
    public function createSettingField(int $position): void
    {
        if (400 === $position) { ?>
            <li class="statik_enableSearch_setting field_setting">
                <input type="checkbox" id="field_statik_enableSearch"
                       onclick="SetFieldProperty('search', this.checked);"/>
                <label for="field_statik_enableSearch" class="section_label">
                    <?= \__('Enable search field', 'statik-luna'); ?>
                    <?= \gform_tooltip('form_field_statik_enableSearch', true); ?>
                </label>
            </li>
        <?php }
    }

    /**
     * Register custom tooltips that are required for the field.
     */
    public function addCustomTooltip(array $tooltips): array
    {
        $tooltips['form_field_statik_enableSearch'] = \sprintf(
            '<strong>%s</strong>%s',
            \__('Enable Search', 'statik-luna'),
            \__(
                'Select this option to render the search input field. This option requires to Enhanced UI be enabled.',
                'statik'
            )
        );

        return $tooltips;
    }

    /**
     * Register new field in Statik GraphQL plugin.
     */
    public function addGraphQlProperty(mixed $config, string $typeName): mixed
    {
        if ($typeName !== 'MultiSelectField' || false === \is_array($config)) {
            return $config;
        }

        $config['fields']['enableSearch'] = [
            'type' => 'Boolean',
            'description' => \__('Determines if the select field should be rendered with enhanced UI way.', 'statik-luna'),
            'resolve' => static fn (GF_Field $field): bool => (bool) $field['enableSearch'],
        ];

        return $config;
    }
}
