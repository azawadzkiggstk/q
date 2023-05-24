<?php

declare(strict_types=1);

namespace Statik\Luna\GravityForms\Properties;

\defined('ABSPATH') || exit('Direct access is not permitted!');

use GF_Field;

/**
 * Class MinMaxDateProperty.
 */
class MinMaxDateProperty
{
    /**
     * MinMaxDateProperty constructor.
     */
    public function __construct()
    {
        \add_filter('Statik\GraphQL\registerTypeConfig', [$this, 'addGraphQlProperty'], 10, 2);
        \add_filter('gform_field_validation', [$this, 'validateField'], 10, 4);

        if ('gf_edit_forms' === ($_GET['page'] ?? false)) {
            \add_filter('admin_footer', [$this, 'enqueueAssets']);
            \add_action('gform_field_standard_settings', [$this, 'createSettingField']);
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
            ([field]) => {
              document.getElementById('field_statik_minDate').value = field.minDate || ''
              document.getElementById('field_statik_maxDate').value = field.maxDate || ''
            }
          ))()
        </script>
        <?php
    }

    /**
     * Add settings field in the Gravity Forms form editor screen.
     */
    public function createSettingField(int $position): void
    {
        if (1600 === $position) { ?>
            <li class="statik_minDate_setting field_setting">
                <label for="field_statik_minDate" class="section_label">
                    <?= \__('Minimum Date', 'statik-luna'); ?>
                    <?= \gform_tooltip('form_field_statik_minDate', true); ?>
                </label>
                <input type="date" id="field_statik_minDate"
                       style="max-height:2.25rem;min-height:2.25rem;width:100%;padding:0 .75rem;"
                       onchange="SetFieldProperty('minDate', this.value);"
                       onkeypress="SetFieldProperty('minDate', this.value);"/>
            </li>
            <li class="statik_maxDate_setting field_setting">
                <label for="field_statik_maxDate" class="section_label">
                    <?= \__('Maximum Date', 'statik-luna'); ?>
                    <?= \gform_tooltip('form_field_statik_maxDate', true); ?>
                </label>
                <input type="date" id="field_statik_maxDate"
                       style="max-height:2.25rem;min-height:2.25rem;width:100%;padding:0 .75rem;"
                       onchange="SetFieldProperty('maxDate', this.value);"
                       onkeypress="SetFieldProperty('maxDate', this.value);"/>
            </li>
        <?php }
    }

    /**
     * Register custom tooltips that are required for the field.
     */
    public function addCustomTooltip(array $tooltips): array
    {
        $tooltips['form_field_statik_minDate'] = \sprintf(
            '<strong>%s</strong>%s',
            \__('Minimum Date', 'statik-luna'),
            \__('Enter the minimum date that this field has to have.', 'statik-luna')
        );

        $tooltips['form_field_statik_maxDate'] = \sprintf(
            '<strong>%s</strong>%s',
            \__('Maximum Date', 'statik-luna'),
            \__('Enter the maximum date that this field is allowed to have.', 'statik-luna')
        );

        return $tooltips;
    }

    /**
     * Display custom validation error in the frontend.
     */
    public function validateField(array $result, mixed $value, array $form, GF_Field $field): array
    {
        if ('date' !== $field->type) {
            return $result;
        }

        if (
            \property_exists($field, 'minDate')
            && $field->maxDate
            && \strtotime($value) > \strtotime($field->maxDate)
        ) {
            $result['is_valid'] = false;
            $result['message'] = \__('The date entered is after the maximum allowed date.', 'statik-luna');
        }

        if (
            \property_exists($field, 'maxDate')
            && $field->minDate
            && \strtotime($value) < \strtotime($field->minDate)
        ) {
            $result['is_valid'] = false;
            $result['message'] = \__('The date entered is before the minimum allowed date.', 'statik-luna');
        }

        return $result;
    }

    /**
     * Register new field in Statik GraphQL plugin.
     */
    public function addGraphQlProperty(mixed $config, string $typeName): mixed
    {
        if ($typeName !== 'DateField' || false === \is_array($config)) {
            return $config;
        }

        $config['fields']['minDate'] = [
            'type' => 'Integer',
            'description' => \__('Specifies the minimum date required in a date field.', 'statik-luna'),
            'resolve' => static fn (GF_Field $field): ?int => \strtotime($field['minDate']) ?: null,
        ];

        $config['fields']['maxDate'] = [
            'type' => 'Integer',
            'description' => \__('Specifies the minimum date required in a date field.', 'statik-luna'),
            'resolve' => static fn (GF_Field $field): ?int => \strtotime($field['maxDate']) ?: null,
        ];

        return $config;
    }
}
