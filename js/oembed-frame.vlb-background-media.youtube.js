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
tag.src = "//youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

ready(function() {
  var media_iframe = document.querySelector('iframe');
  media_iframe.setAttribute('id', 'media-oembed-iframe');

  var player_confgured = false;
  var youtube_player;

  function actionProcessor(evt) {
    // Manage Youtube video.
    if (evt.data === "play") {
      if((typeof(YT) == "undefined" || typeof(YT.Player) == "undefined") && !window.loadingPlayer){
        window.loadingPlayer = true;
        window.onYouTubeIframeAPIReady = function() {
          handlePlayer();
        };
      }else{
        handlePlayer();
      }
    }
  }

  function handlePlayer(){
    var youtube_iframe = document.querySelector('iframe[src*="youtube.com"]');
    if (youtube_iframe !== undefined && youtube_iframe.src !== undefined) {

      if (!player_confgured) {
        var youtubeURL = String(youtube_iframe.src);
        youtubeURL = youtubeURL.replace(/autoplay=0/gi, "autoplay=1");
        youtubeURL = youtubeURL.replace(/controls=0/gi, "controls=1");
        youtubeURL = youtubeURL + "&controls=0";
        youtubeURL = youtubeURL + "&enablejsapi=1";
        youtubeURL = youtubeURL + "&showinfo=0";        // Hide the video title
        youtubeURL = youtubeURL + "&modestbranding=1";  // Hide the Youtube Logo
        youtubeURL = youtubeURL + "&loop=1";            // Run the video in a loop
        youtubeURL = youtubeURL + "&fs=1";              // Hide the full screen button
        youtubeURL = youtubeURL + "&cc_load_policy=1";  // Hide closed captions
        youtubeURL = youtubeURL + "&iv_load_policy=1";  // Hide the Video Annotations
        youtubeURL = youtubeURL + "&volume=0";
        youtubeURL = youtubeURL + "&rel=0";
        youtube_iframe.src = youtubeURL;
        youtubeURL = undefined;

        youtube_player = new YT.Player(youtube_iframe.id, {
          playerVars: {
            autoplay: 1,        // Auto-play the video on load
            controls: 0,        // Show pause/play buttons in player
            showinfo: 0,        // Hide the video title
            modestbranding: 1,  // Hide the Youtube Logo
            loop: 1,            // Run the video in a loop
            fs: 0,              // Hide the full screen button
            autohide: 0,        // Hide video controls when playing
            rel: 0              // Hide related videos
          },
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });

        function onPlayerReady(event) {
          event.target.mute();
          event.target.setVolume(0);
          event.target.playVideo();
        }

        function onPlayerStateChange(event) {
          if (event.data === YT.PlayerState.ENDED) {
            youtube_player.isPlaying = false;
            youtube_player.seekTo(0);
            youtube_player.playVideo();
          }else if (event.data === YT.PlayerState.PLAYING) {
            youtube_player.isPlaying = true;
          }
          else {
            youtube_player.isPlaying = false;
          }
        }

        player_confgured = true;
      }

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
