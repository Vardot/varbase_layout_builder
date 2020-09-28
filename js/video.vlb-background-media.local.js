/**
 * @file
 * Behaviors of Varbase Layout Builder for local video scripts.
 */

(function($, _, Drupal) {
  Drupal.behaviors.varbaseLayoutBuilder_local_video = {
    attach: function() {
      const player = $(".background-media-wrapper video").get(0);
      // Play local video on load of the page.
      if (player) {
        player.play();
        player.onpause = onPause;
        player.onended = onFinish;
      }

      function onPause() {
        $(".background-media-wrapper video").trigger("play");
      }

      // Play when finished.
      function onFinish() {
        $(".background-media-wrapper video").trigger("play");
      }
    }
  };
})(window.jQuery, window._, window.Drupal);
