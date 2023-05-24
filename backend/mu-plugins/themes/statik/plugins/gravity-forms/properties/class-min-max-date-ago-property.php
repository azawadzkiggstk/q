<?php

declare(strict_types=1);

namespace Statik\Luna\GravityForms\Properties;

\defined('ABSPATH') || exit('Direct access is not permitted!');

use GF_Field;

/**
 * Class MinMaxDateAgoProperty.
 */
class MinMaxDateAgoProperty
{
    /**
     * MinMaxDateAgoProperty constructor.
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
              document.getElementById('field_statik_minDateAgo').value = field.minDateAgo || ''
              document.getElementById('field_statik_maxDateAgo').value = field.maxDateAgo || ''
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
            <li class="statik_dateAgoRange_setting field_setting">
                <div style="clear:both;">
                    <label class="section_label"><?php \esc_html_e('Past years range', 'statik-luna'); ?>
                        <?php \gform_tooltip('form_field_statik_dateAgoRange'); ?>
                    </label>
                </div>
                <div class="range_min">
                    <input type="text" id="field_statik_minDateAgo"
                           onchange="SetFieldProperty('minDateAgo', this.value);"
                           onkeypress="return ValidateKeyPress(event, GetMaxLengthPattern(), false);"/>
                    <label for="field_statik_minDateAgo"><?= \__('Min years ago', 'statik-luna'); ?></label>
                </div>
                <div class="range_max">
                    <input type="text" id="field_statik_maxDateAgo"
                           onchange="SetFieldProperty('maxDateAgo', this.value);"
                           onkeypress="return ValidateKeyPress(event, GetMaxLengthPattern(), false);"/>
                    <label for="field_statik_maxDateAgo"><?= \__('Max years ago', 'statik-luna'); ?></label>
                </div>
                <br class="clear"/>
            </li>
        <?php }
    }

    /**
     * Register custom tooltips that are required for the field.
     */
    public function addCustomTooltip(array $tooltips): array
    {
        $tooltips['form_field_statik_dateAgoRange'] = \sprintf(
            '<strong>%s</strong>%s',
            \__('Range Ago', 'statik-luna'),
            \__('Enter the minimum or maximum past years range that this field has to have.', 'statik-luna')
        );

        return $tooltips;
    }

    /**
     * Display custom validation error in the frontend.
     */
    public function validateField(array $result, mixed $value, array $form, GF_Field $field): array
    {
        if ('date' !== $field->type || empty($value)) {
            return $result;
        }

        if (
            \property_exists($field, 'minDateAgo')
            && $field->maxDateAgo
            && \strtotime($value) < \time() - $field->maxDateAgo * YEAR_IN_SECONDS
        ) {
            $result['is_valid'] = false;
            $result['message'] = \sprintf(
                \__('The date entered has to be no more than %s years ago.', 'statik-luna'),
                $field->maxDateAgo
            );
        }

        if (
            \property_exists($field, 'minDateAgo')
            && $field->minDateAgo
            && \strtotime($value) > \time() - $field->minDateAgo * YEAR_IN_SECONDS
        ) {
            $result['is_valid'] = false;
            $result['message'] = \sprintf(
                \__('The date entered has to be at least %s years ago.', 'statik-luna'),
                $field->minDateAgo
            );
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

        $config['fields']['minDateAgo'] = [
            'type' => 'Integer',
            'description' => \__('Specifies the minimum date required in a date field.', 'statik-luna'),
            'resolve' => static fn (GF_Field $field): ?int => $field['minDateAgo'] ? (int) $field['minDateAgo'] : null,
        ];

        $config['fields']['maxDateAgo'] = [
            'type' => 'Integer',
            'description' => \__('Specifies the minimum date required in a date field.', 'statik-luna'),
            'resolve' => static fn (GF_Field $field): ?int => $field['maxDateAgo'] ? (int) $field['maxDateAgo'] : null,
        ];

        return $config;
    }
}
