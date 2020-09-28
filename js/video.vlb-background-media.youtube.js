/**
 * @file
 * Behaviors of Varbase Layout Builder for Youtube video scripts.
 */

(function($, _, Drupal) {
  Drupal.behaviors.varbaseLayoutBuilder_youtube = {
    attach: function(context) {
      if (context === window.document) {
        $(document).ready(function() {
          if (
            $(".background-media-wrapper").find('iframe[src*="youtube.com"]')
              .length > 0
          ) {
            const closestYoutubeIframe = $(".background-media-wrapper")
              .find('iframe[src*="youtube.com"]')
              .get(0).contentWindow;
            closestYoutubeIframe.postMessage("play", "*");
          }
        });
      }
    }
  };
})(window.jQuery, window._, window.Drupal);
