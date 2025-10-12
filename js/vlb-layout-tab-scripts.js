/**
 * @file
 * Behaviors Bootstrap Layout Builder general scripts.
 */

(function vlbLayoutTabScripts($, _, Drupal) {
  /**
   * Handle click event on breakpoint column.
   */
  function handleColClick() {
    $(this)
      .parents('.blb_breakpoint_cols')
      .find('.blb_breakpoint_col')
      .removeClass('bp-selected');
    $(this)
      .parents('.blb_breakpoint_cols')
      .find('input')
      .prop('checked', false);
    $(this).parents('label').parent().find('input').prop('checked', true);
    $(this)
      .parents('label')
      .find('.blb_breakpoint_col')
      .addClass('bp-selected');
  }

  /**
   * Process each column configuration.
   *
   * @param {number} numOfCols - Total number of columns.
   * @param {string} colClasses - CSS classes for the column.
   * @param {jQuery} colsLabel - jQuery object for the column label.
   *
   * @return {Function} Callback function for $.each iteration.
   */
  function processColConfig(numOfCols, colClasses, colsLabel) {
    return function eachColConfig(index, value) {
      const width = (value / numOfCols) * 100;
      $('<div />', {
        text: `${width.toFixed(0)}%`,
        style: `width:${width};`,
        class: colClasses,
      })
        .appendTo(colsLabel)
        .on('click', handleColClick);
    };
  }

  // Configure Section.
  Drupal.behaviors.bootstrapLayoutBuilderConfigureSection = {
    attach(context) {
      // Graphical Layout Columns
      $('.blb_breakpoint_cols', context).each(function eachBreakpoint() {
        const numOfCols = 12;
        // .custom-control, .custom-radio to solve Bario issues.
        $(this)
          .find('.form-item, .custom-control, .custom-radio')
          .each(function eachFormItem() {
            const cols = $(this).find('input').val().replace('blb_col_', '');
            const colsConfig = cols.split('_');
            const colsLabel = $(this).find('label');
            let colClasses = 'blb_breakpoint_col';
            const checked = $(this).find('input').prop('checked');
            if (typeof checked !== typeof undefined && checked !== false) {
              colClasses += ' bp-selected';
            }

            // Wrap our radio labels and display as a tooltip.
            colsLabel.wrapInner('<div class="bs_tooltip bs_tooltip-lg"></div>');

            // Provide a graphical representation of the columns via some nifty divs styling.
            $.each(
              colsConfig,
              processColConfig(numOfCols, colClasses, colsLabel),
            );
          });
      });

      // Auto-sized textareas.
      $('textarea.blb-auto-size', context).each(function eachTextarea() {
        this.setAttribute(
          'style',
          `height:${
            this.scrollHeight
          }px;overflow-y:hidden;min-height:60px!important;padding:.65rem 1rem;`,
        );
      });
    },
  };
})(window.jQuery, window._, window.Drupal);
