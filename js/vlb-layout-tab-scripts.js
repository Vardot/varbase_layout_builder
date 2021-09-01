/**
 * @file
 * Behaviors Bootstrap Layout Builder general scripts.
 */

(function ($, _, Drupal) {
  // Configure Section.
  Drupal.behaviors.bootstrapLayoutBuilderConfigureSection = {
    attach: function (context) {
      // Graphical Layout Columns
      $(".blb_breakpoint_cols", context).each(function () {
        const numOfCols = 12;
        // .custom-control, .custom-radio to solve Bario issues.
        $(this)
          .find(".form-item, .custom-control, .custom-radio")
          .each(function () {
            const cols = $(this).find("input").val().replace("blb_col_", "");
            const colsConfig = cols.split("_");
            const colsLabel = $(this).find("label");
            let colClasses = "blb_breakpoint_col";
            const checked = $(this).find("input").prop("checked");
            if (typeof checked !== typeof undefined && checked !== false) {
              colClasses += " bp-selected";
            }

            // Wrap our radio labels and display as a tooltip.
            colsLabel.wrapInner('<div class="bs_tooltip bs_tooltip-lg"></div>');

            // Provide a graphical representation of the columns via some nifty divs styling.
            $.each(colsConfig, function (index, value) {
              const width = (value / numOfCols) * 100;
              $("<div />", {
                text: width.toFixed(0) + "%",
                style: "width:" + width + "%;",
                class: colClasses
              })
                .appendTo(colsLabel)
                .on("click", function () {
                  $(this)
                    .parents(".blb_breakpoint_cols")
                    .find(".blb_breakpoint_col")
                    .removeClass("bp-selected");
                  $(this)
                    .parents(".blb_breakpoint_cols")
                    .find("input")
                    .prop("checked", false);
                  $(this)
                    .parents("label")
                    .parent()
                    .find("input")
                    .prop("checked", true);
                  $(this)
                    .parents("label")
                    .find(".blb_breakpoint_col")
                    .addClass("bp-selected");
                });
            });
          });
      });

      // Auto-sized textareas.
      $("textarea.blb-auto-size", context).each(function () {
        this.setAttribute(
          "style",
          "height:" +
            this.scrollHeight +
            "px;overflow-y:hidden;min-height:60px!important;padding:.65rem 1rem;"
        );
      });
    }
  };
})(window.jQuery, window._, window.Drupal);
