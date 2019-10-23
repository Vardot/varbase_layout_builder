/**
 * @file
 * Behaviors of Varbase Layout Builder for Youtube video scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";

  Drupal.behaviors.varbaseLayoutBuilder_youtube = {
    attach: function (context, settings) {
      if (context === window.document) {
        $(document).ready(function() {
          if ($('.vlb-background-media').find('.media--type-remote-video iframe[src*="youtube.com"]').length > 0) {
            var closestYoutubeIframe = $('.vlb-background-media').find('.media--type-remote-video iframe[src*="youtube.com"]').get(0).contentWindow;
            closestYoutubeIframe.postMessage('play', "*");
          }
        });
      }
    }
  }

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
