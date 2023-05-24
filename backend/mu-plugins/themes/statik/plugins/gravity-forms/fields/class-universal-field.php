<?php

declare(strict_types=1);

namespace Statik\Luna\GravityForms\Fields;

\defined('ABSPATH') || exit('Direct access is not permitted!');

/**
 * Class UniversalField.
 */
class UniversalField extends \GF_Field_Text
{
    /** @var string */
    public $type = 'universal';

    /**
     * Returns the field's form editor title.
     */
    public function get_form_editor_field_title(): string
    {
        return \__('Universal', 'statik-luna');
    }

    /**
     * Returns the field's form editor description.
     */
    public function get_form_editor_field_description(): string
    {
        return \__('Allows to create custom universal field.', 'statik-luna');
    }

    /**
     * Returns the field's form editor icon.
     */
    public function get_form_editor_field_icon(): string
    {
        return 'gform-icon--square';
    }

    /**
     * {@inheritDoc}
     */
    public function get_form_editor_button(): array
    {
        return [
            'group' => 'advanced_fields',
            'text' => $this->get_form_editor_field_title(),
            'icon' => $this->get_form_editor_field_icon(),
            'description' => $this->get_form_editor_field_description(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    function get_form_editor_field_settings(): array
    {
        return array_merge(parent::get_form_editor_field_settings(), ['statik_universal_setting']);
    }
}
