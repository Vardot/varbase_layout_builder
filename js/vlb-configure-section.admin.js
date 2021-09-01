/**
 * @file
 * Behaviors Varbase Layout Builder general scripts.
 */

(function ($, _, Drupal) {
  // Fix CKEditor text fields disabled when using inside layout builder modal.
  Drupal.behaviors.varbaseLayoutBuilderCkeditoreWithModal = {
    attach: function () {
      const origAllowInteraction = $.ui.dialog.prototype._allowInteraction;
      $.ui.dialog.prototype._allowInteraction = function (event) {
        if ($(event.target).closest(".cke_dialog").length) {
          return true;
        }
        return origAllowInteraction.apply(this, arguments);
      };
    }
  };
})(window.jQuery, window._, window.Drupal);
