annotation.video = {
    idVideoJs: 'videoContainer',
    idVideo: 'videoContainer_html5_api',
    fps: 25, // frames por segundo
    timeInterval: 1 / 25, // intervalo entre frames - 0.04s = 40ms
    originalDimensions: {
        width: 852,
        height: 480
    },
    player: null,
    frameFromTime(timeSeconds) {
        return parseInt(timeSeconds * annotation.video.fps) + 1;
    },
    timeFromFrame(frameNumber) {
        return ((frameNumber - 1) * annotation.video.timeInterval);
    },
    framesRange: {
        first: 1,
        last: 1
    },
    playingRange: null,
    gotoFrame(frameNumber) {
        let time = annotation.video.timeFromFrame(frameNumber);
        annotation.video.player.currentTime(time);
    },
    enablePlayPause() {
        $btn = document.querySelector(".vjs-play-control");
        if ($btn) {
            $btn.disabled = false;
            $btn.style.color = "white";
            $btn.style.cursor = "pointer";
        }
    },
    disablePlayPause() {
        $btn = document.querySelector(".vjs-play-control");
        if ($btn) {
            $btn.disabled = true;
            $btn.style.color = "grey";
            $btn.style.cursor = "default";
        }
    },
    playRange(range) {
        annotation.video.playingRange = range;
        annotation.video.gotoFrame(range.startFrame)
        annotation.video.player.play();
    }
}
