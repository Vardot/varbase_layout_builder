/**
 * @file
 * Behaviors Varbase Layout Builder general scripts.
 */

(function ($, _, Drupal) {
  // Fix CKEditor text fields disabled when using inside layout builder modal.
  Drupal.behaviors.varbaseLayoutBuilderCkeditoreWithModal = {
    attach() {
      const origAllowInteraction = $.ui.dialog.prototype._allowInteraction;
      $.ui.dialog.prototype._allowInteraction = function (event) {
        if ($(event.target).closest('.cke_dialog').length) {
          return true;
        }
        // eslint-disable-next-line prefer-rest-params
        return origAllowInteraction.apply(this, arguments);
      };
    },
  };
})(window.jQuery, window._, window.Drupal);
