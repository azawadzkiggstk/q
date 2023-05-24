(($) => {
  window.statikGravityForms = window.statikGravityForms || {};
  window.statikGravityForms.instance = {
    /**
     * Show supported field types.
     */
    showSupportedFieldTypes: () => {
      const $gravityFormFieldsGroups = $('.gforms_edit_form .panel-block-tabs__wrapper');
      const $gravityFormAddButtons = $('.gforms_edit_form ul.add-buttons li');

      Object.keys(window.statikGravityForms.config).forEach((key) => {
        if (window.statikGravityForms.config[key].length) {
          $gravityFormAddButtons.find('button[data-type="' + key + '"]').parent().show();
        }
      });

      $gravityFormAddButtons.each((index, el) => {
        const $button = $(el);
        const buttonType = $button.find('button').data('type');

        buttonType in window.statikGravityForms.config ? $button.show() : $button.remove();
      })

      $gravityFormFieldsGroups.each((index, el) => {
        const $fieldGroup = $(el);

        $fieldGroup.find('ul.add-buttons li').length ? $fieldGroup.show() : $fieldGroup.remove();
      });
    },

    /**
     * Hide unsupported settings for field.
     *
     * @param settingsArray
     * @param field
     */
    hideUnsupportedFieldSettings: (settingsArray, field) => {
      return 'type' in field && field.type in window.statikGravityForms.config
        ? window.statikGravityForms.config[field.type]
        : settingsArray;
    },
  };

  window.statikGravityForms.instance.showSupportedFieldTypes();

  window.gform.addFilter('gform_editor_field_settings', (settingsArray, field) =>
    window.statikGravityForms.instance.hideUnsupportedFieldSettings(settingsArray, field)
  );
})(window.jQuery, window.gform);
