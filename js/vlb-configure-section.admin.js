/**
 * @file
 * Behaviors Varbase Layout Builder general scripts.
 */

(function($, _, Drupal) {
  // Configure Section Admin behaviors for Varbase Layout Builder.
  Drupal.behaviors.varbaseLayoutBuilderConfigureSectionAdmin = {
    attach: function() {
      $(".vlb_background_color input:radio").each(function() {
        $(this)
          .next("label")
          .addClass($(this).val());
      });

      $(".vlb_width input:radio").each(function() {
        $(this)
          .next("label")
          .addClass($(this).val());
      });

      $(".vlb_gutter input:radio").each(function() {
        $(this)
          .next("label")
          .addClass("gutter-" + $(this).val());
      });

      $(".vlb_column_style_3 input:radio").each(function() {
        $(this)
          .next("label")
          .addClass($(this).val());
      });

      $(".vlb_column_style_2 input:radio").each(function() {
        $(this)
          .next("label")
          .addClass($(this).val());
      });
    }
  };

  // Fix CKEditor text fields disabled when using inside layout builder modal.
  Drupal.behaviors.varbaseLayoutBuilderCkeditoreWithModal = {
    attach: function(context) {
      const origAllowInteraction = $.ui.dialog.prototype._allowInteraction;
      $.ui.dialog.prototype._allowInteraction = function(event) {
        if ($(event.target).closest(".cke_dialog").length) {
          return true;
        }
        return origAllowInteraction.apply(this, arguments);
      };
    }
  };
})(window.jQuery, window._, window.Drupal);
