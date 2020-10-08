function ready(fn) {
  if (document.readyState !== "loading") {
    fn();
  } else if (document.addEventListener) {
    document.addEventListener("DOMContentLoaded", fn);
  } else {
    document.attachEvent("onreadystatechange", function() {
      if (document.readyState !== "loading") fn();
    });
  }
}

const tag = document.createElement("script");
tag.src = "//player.vimeo.com/api/player.js";
const firstScriptTag = document.getElementsByTagName("script")[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

firstScriptTag.onload = function() {
  ready(function() {
    const mediaIframe = document.querySelector("iframe");
    mediaIframe.setAttribute("id", "media-oembed-iframe");

    let playerConfigured = false;
    let vimeoPlayer;

    function actionProcessor(evt) {
      if (typeof window.Vimeo === "undefined") {
        setTimeout(function() {
          actionProcessor(evt);
        }, 500);
      } else {
        handlePlayer(evt);
      }
    }

    function handlePlayer(evt) {
      // Manage Vimeo video.
      if (evt.data === "play") {
        if (!playerConfigured) {
          const vimeoIframe = document.querySelector(
            'iframe[src*="vimeo.com"]'
          );
          const vimeoOptions = {
            background: true,
            muted: true,
            controls: false
          };

          vimeoPlayer = new window.Vimeo.Player(vimeoIframe, vimeoOptions);
          vimeoPlayer.setVolume(0);
          vimeoPlayer.setLoop(true);
          playerConfigured = true;
        }

        vimeoPlayer.ready().then(function() {
          vimeoPlayer.getPaused().then(function(paused) {
            if (paused) {
              vimeoPlayer.play();
            }
          });
        });
      }
    }
    // Setup the event listener for messaging.
    if (window.addEventListener) {
      window.addEventListener("message", actionProcessor, false);
    } else {
      window.attachEvent("onmessage", actionProcessor);
    }
  });
};
