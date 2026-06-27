/**
 * @file
 * Provides Javascript for the Layout Builder UX module.
 */

(($, Drupal, once) => {
  /**
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.LbUX = {
    attach() {
      $(once('lb_ux', '.layout-builder__actions .layout-builder__link')).on(
        'click',
        (event) => {
          $(event.currentTarget)
            .parent()
            .toggleClass('layout-builder__actions--display');
        },
      );

      $(window).on('dialog:aftercreate', (event, dialog, $element) => {
        const id = $element
          .find('[data-layout-builder-target-highlight-id]')
          .attr('data-layout-builder-target-highlight-id');
        if (id) {
          $(`[data-layout-builder-highlight-id="${id}"]`).addClass(
            'layout-builder__block--selected',
          );
        }
      });

      $(window).on('dialog:afterclose', (event, dialog, $element) => {
        $('.layout-builder__block--selected').removeClass(
          'layout-builder__block--selected',
        );
        if (Drupal.offCanvas.isOffCanvas($element)) {
          // Remove the highlight from all elements.
          $('.layout-builder__actions--display').removeClass(
            'layout-builder__actions--display',
          );
        }
      });

      $(once('lb_ux', '.layout-builder__region > .layout-builder-block')).on(
        'click',
        (event) => {
          const currentBlock = $(event.currentTarget);

          /* If block is clicked on while the block is selected, deselect it. */
          if (currentBlock.hasClass('layout-builder__block--selected')) {
            currentBlock
              .find('.layout-builder__actions__block')
              .removeClass('layout-builder__actions--display');

            currentBlock.removeClass('layout-builder__block--selected');

            /* Otherwise, deselect all and select only the current block. */
          } else {
            $('.layout-builder__region > .layout-builder-block').removeClass(
              'layout-builder__block--selected',
            );
            $('.layout-builder__actions--display').removeClass(
              'layout-builder__actions--display',
            );

            currentBlock
              .find('.layout-builder__actions__block')
              .addClass('layout-builder__actions--display');

            currentBlock.addClass('layout-builder__block--selected');
          }
        },
      );
    },
  };

  /**
   * Override Drupal.offCanvas.beforeCreate.
   *
   * @param {Object} settings
   *   Settings related to the composition of the dialog.
   *
   * @return {undefined}
   */
  Drupal.offCanvas.beforeCreate = ({ settings, $element }) => {
    Drupal.offCanvas.removeOffCanvasEvents($element);
    $('body').addClass('js-off-canvas-dialog-open');

    settings.position = {
      my: 'left top',
      at: `${Drupal.offCanvas.getEdge()} top`,
      of: window,
    };

    const setWidth = localStorage.getItem('Drupal.off-canvas.width');
    const position = settings.drupalOffCanvasPosition;
    const height = position === 'side' ? $(window).height() : settings.height;
    const width = position === 'side' ? setWidth || settings.width : '100%';
    settings.height = height;
    settings.width = width;
  };

  /**
   * Override Drupal.offCanvas.beforeClose().
   *
   * @return {undefined}
   */
  Drupal.offCanvas.beforeClose = ({ $element }) => {
    $('body').removeClass('js-off-canvas-dialog-open');
    // Remove all *.off-canvas events
    Drupal.offCanvas.removeOffCanvasEvents($element);
    Drupal.offCanvas.resetPadding();

    // Save current width.
    const container = Drupal.offCanvas.getContainer($element);
    const width = container.attr(`data-offset-${Drupal.offCanvas.getEdge()}`);
    localStorage.setItem('Drupal.off-canvas.width', width);
  };
})(jQuery, Drupal, once);
