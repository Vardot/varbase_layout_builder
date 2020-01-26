/**
 * @file
 * Behaviors Varbase hero slider media general scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";
  
  // Configure Section Admin behaviors for Varbase Layout Builder.
  Drupal.behaviors.varbaseLayoutBuilderObjectFitPolyfill = {
    attach: function (context) {

      $(".background-media-wrapper image",
        ".background-media-wrapper picture",
        ".background-media-wrapper video").each(function () {

        objectFitPolyfill($(this));
        $(window).on('resize', function(){
          objectFitPolyfill($(this));
        });

      });
    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
