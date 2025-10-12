/**
 * @file
 * Behaviors Varbase Layout Builder general scripts.
 */

(function ($, _, Drupal) {
  // Fix CKEditor text fields disabled when using inside layout builder modal.
  Drupal.behaviors.varbaseLayoutBuilderCkeditoreWithModal = {
    attach() {
      const origAllowInteraction = $.ui.dialog.prototype._allowInteraction;
      $.ui.dialog.prototype._allowInteraction = function (event, ...args) {
        if ($(event.target).closest('.cke_dialog').length) {
          return true;
        }
        return origAllowInteraction.apply(this, [event, ...args]);
      };
    },
  };
})(window.jQuery, window._, window.Drupal);
