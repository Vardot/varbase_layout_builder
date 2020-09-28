/**
 * @file
 * Behaviors of Varbase Layout Builder for vimeo embeded videos scripts.
 */

(function($, _, Drupal) {
  Drupal.behaviors.varbaseLayoutBuilder_vimeo = {
    attach: function(context) {
      if (context === window.document) {
        $(document).ready(function() {
          if (
            $(".background-media-wrapper").find('iframe[src*="vimeo.com"]')
              .length > 0
          ) {
            const closestVimeoIframe = $(".background-media-wrapper")
              .find('iframe[src*="vimeo.com"]')
              .get(0).contentWindow;
            closestVimeoIframe.postMessage("play", "*");
          }
        });
      }
    }
  };
})(window.jQuery, window._, window.Drupal);
