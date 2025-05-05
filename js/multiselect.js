'use strict';

/**
 * @file Enables tagged-style multiselect on selected fields.
 */

((Drupal, $) => {

  Drupal.behaviors.personInfoFormMultiselect = {
    attach: (context) => {
      const colorsSelect = $('#edit-colors-favorite', context);
      colorsSelect.select2();
    }
  }

})(Drupal, jQuery);
