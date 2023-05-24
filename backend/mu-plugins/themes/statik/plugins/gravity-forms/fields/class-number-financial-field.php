<?php

declare(strict_types=1);

namespace Statik\Luna\GravityForms\Fields;

use GFCommon;

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * Class NumberFinancialField.
 */
class NumberFinancialField extends \GF_Field_Text
{
    /** @var string */
    public $type = 'number_financial';

    /**
     * Returns the field's form editor title.
     */
    public function get_form_editor_field_title(): string
    {
        return \__('Financial number', 'statik-luna');
    }

    /**
     * Returns the field's form editor description.
     */
    public function get_form_editor_field_description(): string
    {
        return \__('Allows users to enter a financial number.', 'statik-luna');
    }

    /**
     * Returns the field's form editor icon.
     */
    public function get_form_editor_field_icon(): string
    {
        return 'gform-icon--monetization-on';
    }

    /**
     * {@inheritDoc}
     */
    public function sanitize_settings(): void
    {
        $this->rangeMin = GFCommon::to_number($this->rangeMin);
        $this->rangeMax = GFCommon::to_number($this->rangeMax);

        parent::sanitize_settings();
    }

    /**
     * {@inheritDoc}
     */
    function get_form_editor_field_settings(): array
    {
        return array_merge(
            parent::get_form_editor_field_settings(),
            [
                'default_value_setting',
                'custom_number_format_setting',
                'range_setting',
                'statik_numberMask_setting',
                'statik_currency_setting',
            ]
        );
    }
}
