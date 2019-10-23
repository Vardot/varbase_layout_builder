function ready(fn) {
  if (document.readyState != 'loading'){
    fn();
  } else if (document.addEventListener) {
    document.addEventListener('DOMContentLoaded', fn);
  } else {
    document.attachEvent('onreadystatechange', function() {
      if (document.readyState != 'loading')
        fn();
    });
  }
}

var tag = document.createElement('script');
tag.src = "//player.vimeo.com/api/player.js";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

firstScriptTag.onload = function() {
  ready(function() {
    var media_iframe = document.querySelector('iframe');
    media_iframe.setAttribute('id', 'media-oembed-iframe');

    var player_confgured = false;
    var vimeo_player;

    function actionProcessor(evt) {
      if(typeof(Vimeo) == "undefined"){
        setTimeout(function(){actionProcessor(evt)}, 500);
      }else{
        handlePlayer(evt)
      }
    }

    function handlePlayer(evt){
      // Manage Vimeo video.
      if (evt.data === "play") {
        if (!player_confgured) {
          var vimeo_iframe = document.querySelector('iframe[src*="vimeo.com"]');
          var vimeo_options = {
              muted: true,
              controls: false
          };

          vimeo_player = new Vimeo.Player(vimeo_iframe, vimeo_options);
          vimeo_player.setVolume(0);
          vimeo_player.setLoop(true);
          player_confgured = true;
        }

        vimeo_player.ready().then(function() {
          vimeo_player.getPaused().then(function(paused) {
            if (paused) {
              vimeo_player.play();
            }
          });
        });
      }
    }
    // Setup the event listener for messaging.
    if (window.addEventListener) {
      window.addEventListener("message", actionProcessor, false);
    }
    else {
      window.attachEvent("onmessage", actionProcessor);
    }
  });
};
