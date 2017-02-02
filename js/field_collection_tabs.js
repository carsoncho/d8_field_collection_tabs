/**
 * @file
 * Javascript for the media bundle form.
 */

(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.field_collection_tabs = {
    attach: function(context) {
      // TODO: figure out how to handle multiple #tabs (regex maybe?)
     $('#tabs', context).tabs();
    }
  }

})(jQuery, Drupal);
