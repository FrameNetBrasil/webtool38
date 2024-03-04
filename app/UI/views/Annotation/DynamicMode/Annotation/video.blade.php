<script type="text/javascript">
    $(function () {
        window.annotationVideo = {
            state: 'paused',
            frameFromTime(timeSeconds) {
                return parseInt(timeSeconds * annotation.fps) + 1;
            },
            timeFromFrame(frameNumber) {
                return ((frameNumber - 1) * annotation.timeInterval);
            },
            framesRange: {
                first: 1,
                last: 1
            },
            id: 'videoContainer',
            player: null,
        }

        // let videoInfo = Alpine.reactive({
        //     timeCount: 0,
        //     frameCount: 1,
        //     timeDuration: 0,
        //     frameDuration: 0,
        // })
        //
        // Alpine.effect(() => {
        //     document.querySelector('#timeCount').textContent = videoInfo.timeCount;
        //     document.querySelector('#frameCount').textContent = videoInfo.frameCount;
        //     document.querySelector('#timeDuration').textContent = videoInfo.timeDuration;
        //     document.querySelector('#frameDuration').textContent = videoInfo.frameDuration;
        // })

        window.annotationVideo.player = videojs(annotation.idVideo, {
            controls: true,
            autoplay: false,
            preload: "auto",
            playbackRates: [0.2, 0.5, 0.8, 1, 2],
            bigPlayButton: false,
            children: {
                controlBar: {
                    playToggle: true,
                    volumePanel: true,
                    remainingTimeDisplay: false,
                    fullscreenToggle: false,
                    pictureInPictureToggle: false,
                },
                mediaLoader: true,
                loadingSpinner: true,
            }
        });
        let player = window.annotationVideo.player;

        player.player_.handleTechClick_ = function (event) {
            let state = Alpine.store('doStore').currentVideoState;
            if (state === 'creating') {
                objectManager.creatingObject(event);
            } else {
                if (state === 'paused') {
                    player.play();
                }
                if (state === 'playing') {
                    player.pause();
                }
            }
        };

        // button frame forward
        let btnForward = player.controlBar.addChild('button', {}, 0);
        let btnForwardDom = btnForward.el();
        btnForwardDom.innerHTML = '<span class="material-icons-outlined wt-icon">skip_next</span>';
        btnForwardDom.onclick = function () {
            if (annotationVideo.state === 'paused') {
                let currentTime = player.currentTime();
                player.currentTime(currentTime + annotation.timeInterval);
            }
        };
        // button frame backward
        let btnBackward = player.controlBar.addChild('button', {}, 0);
        let btnBackwardDom = btnBackward.el();
        btnBackwardDom.innerHTML = '<span class="material-icons-outlined wt-icon">skip_previous</span>';
        btnBackwardDom.onclick = function () {
            if (annotationVideo.state === 'paused') {
                let currentTime = player.currentTime();
                if (Alpine.store('doStore').frameCount > 1) {
                    player.currentTime(currentTime - annotation.timeInterval);
                }
            }
        };

        player.ready(function () {
            player.on('durationchange', () => {
                let duration = player.duration();
                Alpine.store('doStore').timeDuration = parseInt(duration);
                let lastFrame = annotationVideo.frameFromTime(duration);
                Alpine.store('doStore').frameDuration = lastFrame;
                annotationVideo.framesRange.last = lastFrame;
                Alpine.store('doStore').updateObjectList();
            })
            player.on('timeupdate', () => {
                let currentTime = player.currentTime();
                Alpine.store('doStore').timeCount = parseInt(currentTime);
                Alpine.store('doStore').updateCurrentFrame(annotationVideo.frameFromTime(currentTime));
            })
            player.on('play', () => {
                let state = Alpine.store('doStore').currentVideoState;
                if (state === 'paused') {
                    Alpine.store('doStore').currentVideoState = 'playing';
                }
            })
            player.on('pause', () => {
                Alpine.store('doStore').currentVideoState = 'paused';
            })
        });


    })
</script>

<video-js
    id="videoContainer"
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
<div x-data class="info flex flex-row justify-content-between">
    <div>
        <span x-text="$store.doStore.frameCount"></span> [<span x-text="$store.doStore.timeCount"></span>s]
    </div>
    <div>
        <span x-text="$store.doStore.currentVideoState"></span>
    </div>
    <div>
        <span x-text="$store.doStore.frameDuration"></span> [<span x-text="$store.doStore.timeDuration"></span>s]
    </div>
</div>

