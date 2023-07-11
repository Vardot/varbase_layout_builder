/**
 * @file
 * Behaviors for adding the Gin dark mode  to the Drupal off canvas.
 *
 * Even tho if the user is using the Gin light mode.
 */
(function ($, Drupal, once) {
  Drupal.behaviors.varbaseLayoutBuilderOffCanvas = {
    attach(context) {
      $(once('body', context)).each(function () {
        const drupalOffCanvasWrapper = $('.ui-dialog-off-canvas.ui-dialog-position-side');
        if (drupalOffCanvasWrapper) {
          drupalOffCanvasWrapper.addClass('gin--dark-mode');
        }

        const drupalOffCanvas = $('#drupal-off-canvas');
        if (drupalOffCanvas) {
          drupalOffCanvas.addClass('gin--dark-mode');
        }
      });
    },
  };
})(jQuery, Drupal, once);
