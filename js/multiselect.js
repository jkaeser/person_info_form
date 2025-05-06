'use strict';

/**
 * @file Enables tagged-style multiselect on multi-select fields.
 */

((Drupal, $) => {

  Drupal.behaviors.personInfoFormMultiselect = {
    attach: (context) => {
      const colorsSelect = once('personInfoFormMultiselect', $('.form-select[multiple]', context));
      colorsSelect.forEach(el => {
        $(el).select2()
      });
    }
  }

})(Drupal, jQuery, once);
