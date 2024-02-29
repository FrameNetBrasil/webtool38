<script type="text/javascript">
    $(function () {
        let annotationVideo = {
            fps: 25, // frames por segundo
            timeInterval: 25 / 1000, // intervalo entre frames
            state: 'paused',
            frameFromTime(timeSeconds) {
                return parseInt(timeSeconds * annotationVideo.fps) + 1;
            },
            timeFromFrame(frameNumber) {
                return ((frameNumber - 1) * annotationVideo.timeInterval) / 1000;
            },
        }

        let videoInfo = Alpine.reactive({
            timeCount: 0,
            frameCount: 1,
            timeDuration: 0,
            frameDuration: 0,
        })

        Alpine.effect(() => {
            document.querySelector('#timeCount').textContent = videoInfo.timeCount;
            document.querySelector('#frameCount').textContent = videoInfo.frameCount;
            document.querySelector('#timeDuration').textContent = videoInfo.timeDuration;
            document.querySelector('#frameDuration').textContent = videoInfo.frameDuration;
        })

        const player = videojs('vid1', {
            controls: true,
            autoplay: false,
            preload: "auto",
            playbackRates: [0.2, 0.5, 0.8, 1, 2],
            children: {
                controlBar: {
                    playToggle: true,
                    volumePanel: true,
                    remainingTimeDisplay: false,
                    fullscreenToggle: false,
                    pictureInPictureToggle: false,
                    bigPlayButton: false,

                },
                bigPlayButton: false,
                mediaLoader: true,
                loadingSpinner: true,

            }
        });

        // button frame forward
        let btnForward = player.controlBar.addChild('button', {}, 0);
        let btnForwardDom = btnForward.el();
        btnForwardDom.innerHTML = '<span class="material-icons-outlined wt-icon">skip_next</span>';
        btnForwardDom.onclick = function () {
            console.log(annotationVideo.state);
            if (annotationVideo.state === 'paused') {
                let currentTime = player.currentTime();
                player.currentTime(currentTime + annotationVideo.timeInterval);
            }
        };
        // button frame backward
        let btnBackward = player.controlBar.addChild('button', {}, 0);
        let btnBackwardDom = btnBackward.el();
        btnBackwardDom.innerHTML = '<span class="material-icons-outlined wt-icon">skip_previous</span>';
        btnBackwardDom.onclick = function () {
            console.log(annotationVideo.state);
            if (annotationVideo.state === 'paused') {
                let currentTime = player.currentTime();
                if (videoInfo.frameCount > 1) {
                    player.currentTime(currentTime - annotationVideo.timeInterval);
                }
            }
        };

        player.ready(function () {
            player.on('durationchange', () => {
                let duration = player.duration();
                videoInfo.timeDuration = parseInt(duration);
                videoInfo.frameDuration = annotationVideo.frameFromTime(videoInfo.timeDuration);
            })
            player.on('timeupdate', () => {
                let currentTime = player.currentTime();
                //console.log(player.currentTime(), annotationVideo.state);
                videoInfo.timeCount = parseInt(currentTime);
                videoInfo.frameCount = annotationVideo.frameFromTime(currentTime);
            })
            player.on('play', () => {
                annotationVideo.state = 'playing';
            })
            player.on('pause', () => {
                annotationVideo.state = 'paused';
            })
        });


    })
</script>

<video-js
    id="vid1"
    class="video-js"
    width="852"
    height="480"
>
    <source
        src="https://webtool.framenetbr.ufjf.br/apps/webtool/files/multimodal/Video_Store/full/afa00f72fb6fe767d051f2dff2633ee3e67eecdd.mp4"
        type="video/mp4"/>
    <p class="vjs-no-js">
        To view this video please enable JavaScript, and consider upgrading to a
        web browser that
        <a href="https://videojs.com/html5-video-support/" target="_blank"
        >supports HTML5 video</a
        >
    </p>
</video-js>
<div class="info flex flex-row justify-content-between">
    <div>
        <span id="frameCount"></span> [<span id="timeCount"></span>s]
    </div>
    <div>
        <span id="frameDuration"></span> [<span id="timeDuration"></span>s]
    </div>
</div>

