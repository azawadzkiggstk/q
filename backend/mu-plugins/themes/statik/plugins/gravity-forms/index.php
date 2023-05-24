<?php

declare(strict_types=1);

\defined('ABSPATH') || exit('Direct access is not permitted!');

use Statik\Luna\GravityForms\Fields\NumberCurrencyGraphQl;
use Statik\Luna\GravityForms\Fields\NumberFinancialField;
use Statik\Luna\GravityForms\Fields\NumberFinancialGraphQl;
use Statik\Luna\GravityForms\Fields\UniversalField;
use Statik\Luna\GravityForms\Fields\UniversalGraphQl;
use Statik\Luna\GravityForms\Properties\DisabledProperty;
use Statik\Luna\GravityForms\Properties\MinLengthProperty;
use Statik\Luna\GravityForms\Properties\MinMaxDateAgoProperty;
use Statik\Luna\GravityForms\Properties\MinMaxDateProperty;
use Statik\Luna\GravityForms\Properties\NumberCurrencyProperty;
use Statik\Luna\GravityForms\Properties\NumberMaskProperty;
use Statik\Luna\GravityForms\Properties\SearchProperty;
use Statik\Luna\GravityForms\Properties\UniversalProperty;

if (false === \class_exists('GFForms')) {
    return;
}

\add_action('init', 'statik_add_gravity_forms_support');

\add_action('gform_post_add_entry', 'statik_send_notification_on_entry_create', 10, 2);

\add_action('admin_enqueue_scripts', 'statik_enqueue_gravity_forms_filters');

\add_filter('pre_update_option_gform_enable_noconflict', '__return_false');

if (false === \function_exists('statik_add_gravity_forms_support')) {
    /**
     * Register Gravity Forms fields and properties.
     */
    function statik_add_gravity_forms_support(): void
    {
        \array_map(static fn (string $file) => include_once $file, \glob(__DIR__ . '/properties/*.php'));
        \array_map(static fn (string $file) => include_once $file, \glob(__DIR__ . '/fields/*.php'));

        try {
            GF_Fields::register(new NumberFinancialField());
            GF_Fields::register(new UniversalField());

            new DisabledProperty();
            new MinLengthProperty();
            new MinMaxDateAgoProperty();
            new MinMaxDateProperty();
            new NumberCurrencyProperty();
            new NumberMaskProperty();
            new SearchProperty();
            new UniversalProperty();
        } catch (\Exception $e) {
        }
    }
}

if (false === \function_exists('statik_send_notification_on_entry_create')) {
    /**
     * Trigger e-mail sending once form entry is created.
     */
    function statik_send_notification_on_entry_create(mixed $entry, mixed $form): void
    {
        GFAPI::send_notifications($form, $entry);
    }
}

if (false === \function_exists('statik_enqueue_gravity_forms_filters')) {
    /**
     * Enqueue supplemental scripts for Gravity Forms.
     */
    function statik_enqueue_gravity_forms_filters(): void
    {
        if ('gf_edit_forms' !== ($_GET['page'] ?? false)) {
            return;
        }

        \wp_enqueue_script(
            'statik-gravity-forms-filters',
            \plugin_dir_url(__FILE__) . 'javascripts/gravityFormsHelper.js',
            ['gform_form_editor'],
            \wp_get_theme()->get('Version'),
            true
        );

        \wp_localize_script(
            'statik-gravity-forms-filters',
            'statikGravityForms = window.statikGravityForms || {}; statikGravityForms.config',
            \statik_get_gravity_forms_allowed_fields()
        );
    }
}

if (false === \function_exists('statik_get_gravity_forms_allowed_fields')) {
    /**
     * Get a list of allowed fields in Gravity Forms form edit screen.
     */
    function statik_get_gravity_forms_allowed_fields(): array
    {
        /**
         * Fire Gravity Forms universal field available options.
         *
         * @param array list of fields
         */
        $defaultProperties = (array)\apply_filters(
            'Statik\Luna\gravityFormsDefaults',
            [
                '.description_setting',
                '.label_setting',
                '.rules_setting',
                '.placeholder_setting',
                '.css_class_setting',
                '.size_setting',
                '.admin_label_setting',
                '.error_message_setting',
                '.visibility_setting',
                '.prepopulate_field_setting',
                '.conditional_logic_field_setting',
                '.statik_disabled_setting',
            ]
        );

        return [
            'text' => \array_merge($defaultProperties, [
                '.default_value_setting',
                '.maxlen_setting',
                '.statik_minLength_setting',
            ]),
            'textarea' => \array_merge($defaultProperties, [
                '.rich_text_editor_setting',
                '.default_value_textarea_setting',
                '.placeholder_textarea_setting',
                '.maxlen_setting',
                '.statik_minLength_setting',
            ]),
            'select' => \array_merge($defaultProperties, [
                '.choices_setting',
                '.enable_enhanced_ui_setting',
            ]),
            'multiselect' => \array_merge($defaultProperties, [
                '.choices_setting',
                '.enable_enhanced_ui_setting',
                '.statik_enableSearch_setting',
            ]),
            'fileupload' => \array_merge($defaultProperties, [
                '.file_extensions_setting',
                '.multiple_files_setting',
                '.file_size_setting'
            ]),
            'number' => \array_merge($defaultProperties, [
                '.default_value_setting',
                '.custom_number_format_setting',
                '.range_setting',
            ]),
            'number_financial' => \array_merge($defaultProperties, [
                '.default_value_setting',
                '.custom_number_format_setting',
                '.range_setting',
                '.statik_numberMask_setting',
                '.statik_currency_setting',
            ]),
            'checkbox' => \array_merge($defaultProperties, [
                '.choices_setting',
                '.select_all_choices_setting',
            ]),
            'radio' => \array_merge($defaultProperties, [
                '.choices_setting',
            ]),
            'hidden' => [
                '.label_setting',
                '.default_value_setting',
            ],
            'html' => [
                '.default_value_setting',
                '.label_setting',
                '.content_setting',
                '.field_css_class',
            ],
            'date' => \array_merge($defaultProperties, [
                '.default_value_setting',
                '.date_format_setting',
                '.statik_dateAgoRange_setting',
                '.statik_minDate_setting',
                '.statik_maxDate_setting',
            ]),
            'time' => \array_merge($defaultProperties, [
                '.default_value_setting',
                '.time_format_setting',
                '.sub_labels_setting',
            ]),
            'captcha' => [
                '.description_setting',
                '.label_setting',
                '.captcha_theme_setting',
            ],
            'website' => \array_merge($defaultProperties, [
                '.default_value_setting',
            ]),
            'email' => \array_merge($defaultProperties, [
                '.default_value_setting',
                '.email_confirm_setting',
            ]),
            'phone' => \array_merge($defaultProperties, [
                '.default_value_setting',
                '.custom_phone_format_setting',
                '.field_phone_format',
            ]),
            'consent' => \array_merge($defaultProperties, [
                '.checkbox_label_setting',
            ]),
            'universal' => \array_merge($defaultProperties, [
                '.default_value_setting',
                '.placeholder_setting',
                '.statik_universal_setting',
            ]),
            'submit' => [
                '.submit_type_setting',
                '.submit_image_setting',
                '.submit_text_setting',
                '.conditional_logic_submit_setting'
            ]
        ];
    }
}
