/**
 * @file
 * Behaviors of Varbase Layout Builder for vimeo embeded videos scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";

  Drupal.behaviors.varbaseLayoutBuilder_vimeo = {
    attach: function (context, settings) {
      if (context === window.document) {
        $(document).ready(function() {
          if ($('.background-media-wrapper').find('iframe[src*="vimeo.com"]').length > 0) {
            var closestVimeoIframe = $('.background-media-wrapper').find('iframe[src*="vimeo.com"]').get(0).contentWindow;
            closestVimeoIframe.postMessage('play', "*");
          }
        });
      }
    }
  }

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
