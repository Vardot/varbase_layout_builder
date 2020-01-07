/**
 * @file
 * Behaviors Varbase hero slider media general scripts.
 */

(function ($, _, Drupal, drupalSettings) {
  "use strict";
  
  // Configure Section Admin behaviors for Varbase Layout Builder.
  Drupal.behaviors.varbaseLayoutBuilderConfigureSectionAdmin = {
    attach: function (context) {

      $(".vlb_background_color input:radio").each(function () {
        $(this).next('label').addClass($(this).val());
      });

      $(".vlb_width input:radio").each(function () {
        $(this).next('label').addClass($(this).val());
      });

      $(".vlb_gutter input:radio").each(function () {
        $(this).next('label').addClass('gutter-' + $(this).val());
      });

      $(".vlb_column_style_3 input:radio").each(function () {
        $(this).next('label').addClass($(this).val());
      });

      $(".vlb_column_style_2 input:radio").each(function () {
        $(this).next('label').addClass($(this).val());
      });

    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
