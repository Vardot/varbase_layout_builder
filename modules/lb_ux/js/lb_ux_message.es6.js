/**
 * @file
 * Provides Javascript for the Layout Builder UX module.
 */

(($, Drupal) => {
  const selectors = {
    wrapper: {
      initial: 'data-drupal-messages',
      active: 'js-messages__wrapper',
    },
    message: {
      id: 'data-drupal-message-id',
      active: 'messages__closeable',
      button: 'drupal-message-close',
    },
  };

  const lbMessageList = [];

  /**
   * Button markup for closing message.
   *
   * @param {integer} id
   *s
   * @returns {HTMLButtonElement}
   */
  const buttonClose = (id) => {
    const { button: buttonSelector } = selectors.message;
    const button = document.createElement('button');
    button.innerHTML = `<span class="visually-hidden">${Drupal.t(
      'Close',
    )}</span>`;
    button.setAttribute('data-drupal-close-id', id);
    button.classList.add(buttonSelector);

    return button;
  };

  /**
   * Add classes and properties to message.
   *
   * @param {HTMLElement} message
   *
   * @param {integer} index
   */
  const initMessage = (message, index) => {
    const { id, active } = selectors.message;
    message.classList.add(active);
    message.style.setProperty('--animation-index', index);
    message.setAttribute(id, index);
    message.appendChild(buttonClose(index));
  };

  /**
   * Adds message if not already in the list.
   *
   * @param message
   *
   * @returns {boolean} TRUE if message added.
   */
  const addMessage = (message) => {
    const text = message.textContent.trim();
    const newMessage = lbMessageList.indexOf(text) === -1;
    if (newMessage) {
      lbMessageList.push(text);
      initMessage(message, lbMessageList.length);
    }

    return newMessage;
  };

  /**
   * Make array from NodeList using older browser compatible method.
   *
   * @param {HTMLElement} container
   *
   * @returns {array}
   */
  const getMessages = (container) =>
    Array.prototype.slice.call(container.querySelectorAll('.messages'));

  const initMessages = () => {
    const { initial, active } = selectors.wrapper;
    const { id, button } = selectors.message;

    const lbOuter = document.querySelector(
      `[data-drupal-selector=edit-layout-builder-message] > [${initial}]`,
    );
    // Get the inner container if exists.
    const children = Array.prototype.slice.call(lbOuter.children);
    const lbInner = children
      .filter((element) => !element.classList.contains('messages'))
      .shift();
    const lbContainer = lbInner || lbOuter;

    // Make the layout-builder-message the styled container and add its messages
    if (!lbContainer.classList.contains(active)) {
      lbContainer.classList.add(active);
      getMessages(lbContainer).forEach(addMessage);

      const mainCanvas = document.querySelector(
        '[data-off-canvas-main-canvas]',
      );
      if (mainCanvas) {
        // This event fires when canvas CSS transitions are complete.
        const paddingEdge =
          Drupal.offCanvas.getEdge() === 'right'
            ? 'paddingRight'
            : 'paddingLeft';

        mainCanvas.addEventListener('transitionend', () => {
          lbContainer.style[paddingEdge] = mainCanvas.style[paddingEdge];
          lbContainer.style.paddingTop = mainCanvas.style.paddingTop;
        });
      }

      lbContainer.addEventListener('click', (event) => {
        if (event.target.classList.contains(button)) {
          event.preventDefault();
          lbContainer
            .querySelector(`[${id}="${event.target.dataset.drupalCloseId}"]`)
            .remove();
        }
      });
    }

    // If there are multiple other drupal-data-message
    const containers = Array.prototype.slice
      .call(document.querySelectorAll(`[${initial}]`))
      .filter((element) => !element.isEqualNode(lbOuter));

    // Append their messages to layout-builder-message
    containers.forEach((container) => {
      getMessages(container).forEach((message) => {
        if (addMessage(message)) {
          lbContainer.append(message);
        } else {
          // Remove duplicates instead of appending.
          message.remove();
        }
      });
    });
  };

  /**
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.LbUXMessage = {
    attach: function () {
      initMessages();
    },
  };
})(jQuery, Drupal);
