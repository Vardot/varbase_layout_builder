/**
 * @file
 * Behaviors of Varbase Layout Builder for local video scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";

  Drupal.behaviors.varbaseLayoutBuilder_local_video = {
    attach: function (context, settings) {
      var player = $(".vlb-background-media video").get(0);
      // Play local video on load of the page.
      if(player){
        player.play();
        player.onpause = onPause;
        player.onended = onFinish;
      }

      function onPause() {
        $(".vlb-background-media video").trigger('play');
      }

      // Play when finished.
      function onFinish() {
        $(".vlb-background-media video").trigger('play');
      }
    }
  }

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
