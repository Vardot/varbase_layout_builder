/**
 * @file
 * Behaviors of Varbase Layout Builder AOS - Animate on scroll library.
 * From https://michalsnik.github.io/aos
 * Which located at /libraries/aos
 */

(function scrollEffectsInit($, _, Drupal) {
  Drupal.behaviors.VarbaseLayoutBuilderscrollEffectsInit = {
    attach() {
      AOS.init();
    },
  };
})(window.jQuery, window._, window.Drupal);
