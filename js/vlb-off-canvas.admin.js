/**
 * @file
 * Behaviors for adding the Gin dark mode  to the Drupal off canvas.
 *
 * Even tho if the user is using the Gin light mode.
 */
(function ($, Drupal, once) {
  Drupal.behaviors.varbaseLayoutBuilderOffCanvas = {
    attach(context) {
      $(
        once(
          '#drupal-off-canvas',
          '#drupal-off-canvas-wrapper',
          context,
        ),
      ).each(function () {

        const drupalOffCanvas = $('#drupal-off-canvas');
        if (drupalOffCanvas) {
          drupalOffCanvas.addClass('gin--dark-mode');
        }
  
        const drupalOffCanvasWrapper = $('#drupal-off-canvas-wrapper');
        if (drupalOffCanvasWrapper) {
          drupalOffCanvasWrapper.addClass('gin--dark-mode');
        }
      });
    },
  };
})(jQuery, Drupal, once);
