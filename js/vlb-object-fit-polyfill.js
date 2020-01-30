/**
 * @file
 * Behaviors Varbase Layout Builder Object-Fit Polyfill fix scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";

  // Configure Object-Fit Polyfill behaviors for Varbase Layout Builder.
  Drupal.behaviors.varbaseLayoutBuilderObjectFitPolyfill = {
    attach: function (context) {

      objectFitPolyfill($('.background-media-wrapper .bg'));

      $(window).on('resize', function() {
        objectFitPolyfill($('.background-media-wrapper .bg'));
      });

    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
