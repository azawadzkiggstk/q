<?php

declare(strict_types=1);

namespace Statik\Luna\GravityForms\Properties;

\defined('ABSPATH') || exit('Direct access is not permitted!');

use GF_Field;
use Statik\Luna\GravityForms\Fields\NumberFinancialGraphQl;

/**
 *  Class NumberMaskProperty.
 */
class NumberMaskProperty
{
    /**
     * NumberMaskProperty constructor.
     */
    public function __construct()
    {
        \add_filter('Statik\GraphQL\registerTypeConfig', [$this, 'addGraphQlProperty'], 10, 2);

        if ('gf_edit_forms' === ($_GET['page'] ?? false)) {
            \add_action('gform_field_standard_settings', [$this, 'createSettingField']);
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
            ([field]) => document.getElementById('field_statik_numberMask').value = field.numberMask || '9.999,99'
          ))()
        </script>
        <?php
    }

    /**
     * Add settings field in the Gravity Forms form editor screen.
     */
    public function createSettingField(int $position): void
    {
        if (1450 === $position) { ?>
            <li class="statik_numberMask_setting field_setting">
                <label for="field_statik_numberMask" class="section_label">
                    <?= \__('Input Mask', 'statik-luna'); ?>
                    <?= \gform_tooltip('form_field_statik_numberMask', true); ?>
                </label>
                <select id="field_statik_numberMask" onchange="SetFieldProperty('numberMask', this.value);">
                    <option value="9.999,99">9.999,99</option>
                    <option value="9,999.99">9,999.99</option>
                    <option value="9 999,99">9 999,99</option>
                    <option value="9 999.99">9 999.99</option>
                    <option value="9.999">9.999</option>
                    <option value="9,999">9,999</option>
                </select>
            </li>
        <?php }
    }

    /**
     * Register custom tooltips that are required for the field.
     */
    public function addCustomTooltip(array $tooltips): array
    {
        $tooltips['form_field_statik_numberMask'] = \sprintf(
            '<strong>%s</strong>%s',
            \__('Input Mask', 'statik-luna'),
            \__('Define the number input mask. All inputted values will be sanitized to this format.', 'statik-luna')
        );

        return $tooltips;
    }

    /**
     * Register new field in Statik GraphQL plugin.
     */
    public function addGraphQlProperty(mixed $config, string $typeName): mixed
    {
        if ($typeName !== 'NumberFinancialField' || false === \is_array($config)) {
            return $config;
        }

        $config['fields']['numberMask'] = [
            'type' => 'String',
            'description' => \__('Specifies the input mask required in a number field.', 'statik-luna'),
            'resolve' => static fn (GF_Field $field): ?string => $field['numberMask']
                ? (string) $field['numberMask']
                : null,
        ];

        return $config;
    }
}
