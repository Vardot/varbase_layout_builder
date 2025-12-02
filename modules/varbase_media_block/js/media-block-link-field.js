/**
 * @file
 * Controls link field visibility in Varbase Media Block.
 *
 */

/* eslint-disable func-names */

(function ($, Drupal, once) {
  Drupal.behaviors.varbaseMediaBlockLinkField = {
    attach(context) {
      once('varbase-media-block-link', 'form', context).forEach(
        function (form) {
          const $form = $(form);
          const $tracker = $form.find('.media-bundle-type-tracker');

          if ($tracker.length === 0) {
            return;
          }

          const $linkField = $form.find('.field--name-field-link').first();
          const $mediaField = $form.find('.field--name-field-media').first();

          if ($linkField.length === 0 || $mediaField.length === 0) {
            return;
          }

          // Update link field visibility.
          function updateVisibility(mediaBundle) {
            // Define media types that should NOT show link field.
            const hiddenMediaTypes = ['video', 'remote_video', 'audio', 'file'];

            if (mediaBundle && hiddenMediaTypes.indexOf(mediaBundle) !== -1) {
              // Hide for video, remote_video, audio, file
              $linkField.hide().addClass('js-media-link-hidden');
              $linkField.find('input, textarea').prop('disabled', true);
            } else if (mediaBundle) {
              // Show for image and other media types
              $linkField.show().css('display', '');
              $linkField.removeClass('js-media-link-hidden');
              $linkField.find('input, textarea').prop('disabled', false);
            }
          }

          // Get media bundle via htmx.
          function fetchMediaBundle(mediaId) {
            if (!mediaId) return;

            // Use htmx to fetch media bundle info
            const url = `/varbase-media-block/get-media-bundle/${mediaId}`;

            fetch(url, {
              method: 'GET',
              headers: {
                'Content-Type': 'application/json',
              },
            })
              .then(function (response) {
                return response.json();
              })
              .then(function (data) {
                if (data && data.bundle) {
                  updateVisibility(data.bundle);
                  $tracker
                    .val(data.bundle)
                    .attr('data-current-bundle', data.bundle);
                }
              })
              .catch(function () {
                // Silent fail - form will use PHP default state
              });
          }

          // Check for media selection.
          function checkSelection() {
            const $mediaInput = $form.find(
              'input[name*="media_library_selection"]',
            );
            if ($mediaInput.length > 0 && $mediaInput.val()) {
              const mediaId = $mediaInput.val();
              const lastId = $tracker.attr('data-last-id');
              if (mediaId !== lastId) {
                $tracker.attr('data-last-id', mediaId);
                fetchMediaBundle(mediaId);
              }
            }
          }

          // Initial state.
          const initialBundle = $tracker.val();
          if (initialBundle) {
            updateVisibility(initialBundle);
          }

          // Listen for htmx events and AJAX completion.
          $(document).on(
            'htmx:afterSwap ajaxSuccess',
            function (event, xhr, ajaxSettings) {
              // Check if it's related to media
              if (
                event.type === 'htmx:afterSwap' ||
                (ajaxSettings &&
                  ajaxSettings.url &&
                  ajaxSettings.url.indexOf('media') !== -1)
              ) {
                setTimeout(checkSelection, 100);
                setTimeout(checkSelection, 300);
              }
            },
          );

          // Watch for DOM changes.
          if (window.MutationObserver) {
            const observer = new MutationObserver(function () {
              checkSelection();
            });

            observer.observe($mediaField[0], {
              childList: true,
              subtree: true,
            });
          }

          // Quick periodic check.
          const interval = setInterval(checkSelection, 300);

          $form.on('remove', function () {
            clearInterval(interval);
          });
        },
      );
    },
  };
})(jQuery, Drupal, once);
