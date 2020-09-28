/**
 * @file
 * Behaviors Varbase Layout Builder Object-Fit Polyfill fix scripts.
 */

(function($, _, Drupal, objectFitPolyfill) {
  // Configure Object-Fit Polyfill behaviors for Varbase Layout Builder.
  Drupal.behaviors.varbaseLayoutBuilderObjectFitPolyfill = {
    attach: function(context) {
      objectFitPolyfill($(".background-media-wrapper img.bg", context));
      objectFitPolyfill($(".background-media-wrapper picture.bg", context));
      objectFitPolyfill($(".background-media-wrapper video.bg", context));
    }
  };
})(window.jQuery, window._, window.Drupal, window.objectFitPolyfill);
