<?php

declare(strict_types=1);

namespace Statik\Luna\GravityForms\Properties;

\defined('ABSPATH') || exit('Direct access is not permitted!');

use GF_Field;
use GFCommon;

/**
 * Class MinLengthProperty.
 */
class MinLengthProperty
{
    /**
     * MinLengthProperty constructor.
     */
    public function __construct()
    {
        \add_filter('Statik\GraphQL\registerTypeConfig', [$this, 'addGraphQlProperty'], 10, 2);
        \add_filter('gform_field_validation', [$this, 'validateField'], 10, 4);

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
            ([field]) => document.getElementById('field_statik_minLength').value = field.minLength || ''
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
            <li class="statik_minLength_setting field_setting">
                <label for="field_statik_minLength" class="section_label">
                    <?= \__('Minimum Characters', 'statik-luna'); ?>
                    <?= \gform_tooltip('form_field_statik_minLength', true); ?>
                </label>
                <input type="text" id="field_statik_minLength"
                       onchange="SetFieldProperty('minLength', this.value);"
                       onkeypress="return ValidateKeyPress(event, GetMaxLengthPattern(), false);"/>
            </li>
        <?php }
    }

    /**
     * Register custom tooltips that are required for the field.
     */
    public function addCustomTooltip(array $tooltips): array
    {
        $tooltips['form_field_statik_minLength'] = \sprintf(
            '<strong>%s</strong>%s',
            \__('Minimum Characters', 'statik-luna'),
            \__('Enter the minimum number of characters that this field has to have.', 'statik-luna')
        );

        return $tooltips;
    }

    /**
     * Display custom validation error in the frontend.
     */
    public function validateField(array $result, mixed $value, array $form, GF_Field $field): array
    {
        if ('text' !== $field->type) {
            return $result;
        }

        if (
            \property_exists($field, 'minLength')
            && $field->minLength
            && GFCommon::safe_strlen($value) < (int) $field->minLength
        ) {
            $result['is_valid'] = false;
            $result['message'] = \__('The text entered does not contain the minimum number of characters.', 'statik-luna');
        }

        return $result;
    }

    /**
     * Register new field in Statik GraphQL plugin.
     */
    public function addGraphQlProperty(mixed $config, string $typeName): mixed
    {
        if (false === \in_array($typeName, ['TextField', 'TextAreaField']) || false === \is_array($config)) {
            return $config;
        }

        $config['fields']['minLength'] = [
            'type' => 'Integer',
            'description' => \__(
                'Specifies the minimum number of characters required in a text or textarea (paragraph) field.',
                'statik'
            ),
            'resolve' => static fn (GF_Field $field): int => (int) $field['minLength'],
        ];

        return $config;
    }
}
