/**
 * @file
 * Behaviors Varbase Layout Builder Object-Fit Polyfill fix scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";

  // Configure Object-Fit Polyfill behaviors for Varbase Layout Builder.
  Drupal.behaviors.varbaseLayoutBuilderObjectFitPolyfill = {
    attach: function (context) {

      $('.background-media-wrapper > image',
        '.background-media-wrapper > picture',
        '.background-media-wrapper > video',
        '.background-media-wrapper > iframe[src*="youtube.com"]',
        '.background-media-wrapper > iframe[src*="vimeo.com"]').each(function () {

        objectFitPolyfill($(this));
        $(window).on('resize', function(){
          objectFitPolyfill($(this));
        });

      });
    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
