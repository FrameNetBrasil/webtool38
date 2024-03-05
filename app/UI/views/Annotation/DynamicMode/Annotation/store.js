document.addEventListener('alpine:init', () => {
    window.doStore = Alpine.store('doStore', {
        dataState: '',
        timeCount: 0,
        frameCount: 1,
        timeDuration: 0,
        frameDuration: 0,
        currentVideoState: 'paused',
        currentFrame: 1,
        currentObject: null,
        currentObjectState: 'none',
        newObjectState: 'none',
        init() {
            annotation.objects.init();
        },
        config() {
            let config = {
                idVideoDOMElement: annotation.video.idVideo,
                fps: annotation.video.fps,
            }
            annotation.objects.config(config);
        },
        async updateObjectList() {
            this.dataState = 'loading';
            await annotation.api.loadObjects();
        },
        updateCurrentFrame(frameNumber) {
            this.frameCount = this.currentFrame = frameNumber;
            if ((this.currentVideoState === 'paused') || (this.newObjectState === 'tracking') || (this.newObjectState === 'editing')) {
                console.error('===================');
                annotation.objects.drawFrameObject(frameNumber);
            }
        },
        selectObject(idObject) {
            if (idObject === null) {
                this.currentObject = null;
                annotation.objects.clearFrameObject();
            } else {
                let object = annotation.objects.get(idObject);
                this.currentObject = object;
                console.log(object);
                let time = annotation.video.timeFromFrame(object.object.startFrame);
                console.log(time, object.object.startFrame);
                annotation.video.player.currentTime(time);
                annotation.objects.drawFrameObject(object.object.startFrame);
            }
            annotationGridObject.selectRowByObject(idObject);
            this.newObjectState = 'editing';
            this.currentVideoState = 'editing';
        },
        selectObjectByIdObjectMM(idObjectMM) {
            let object = annotation.objects.getByIdObjectMM(idObjectMM);
            this.selectObject(object.idObject);
        },
        selectObjectFrame(idObject, frameNumber) {
            this.currentObject = annotation.objects.get(idObject);
            annotationGridObject.selectRowByObject(idObject);
            let time = annotation.video.timeFromFrame(frameNumber);
            annotation.video.player.currentTime(time);
            annotation.objects.drawFrameObject(frameNumber);
        },
        createObject() {
            if (this.currentVideoState === 'paused') {
                console.log('create object');
                this.newObjectState = 'creating';
                this.currentVideoState = 'creating';
                this.selectObject(null);
                //document.querySelector('#' + annotation.idVideo).style.cursor = 'crosshair';
                annotation.objects.creatingObject();
            }
        },
        endObject() {
            console.log('end object');
            this.newObjectState = 'none';
            this.currentVideoState = 'paused';

            // if ((this.currentState === 'objectEditing')
            //     || (this.currentState === 'videoPaused')
            //     || (this.currentState === 'objectPaused')) {
            //     //this.$store.dispatch('endObject');
            //     // this.$store.commit('currentMode', 'video');
            //     this.$store.dispatch('currentObjectEndFrame', this.$store.state.currentFrame);
            //     console.log('ending frame = ', this.$store.state.currentObject.endFrame)
            //     this.$store.commit('currentState', 'videoPaused');
            //     dynamicObjects.saveCurrentObject();
            //     this.$store.commit('updateObjectPane', true);
            // }

        },
        startTracking() {
            console.log('start tracking');
            this.newObjectState = 'tracking';
            this.currentVideoState = 'tracking';
            annotation.objects.tracking(true);

        },
        pauseTracking() {
            console.log('pause tracking');
            this.newObjectState = 'editing';
            this.currentVideoState = 'editing';
        },
    })

    Alpine.effect(() => {
        const timeCount = Alpine.store('doStore').timeCount;
        //console.log('timecount change', timeCount);
    })
    Alpine.effect(() => {
        const frameCount = Alpine.store('doStore').frameCount;
        //console.log('framecount change', frameCount);
    })
    Alpine.effect(() => {
        const currentVideoState = Alpine.store('doStore').currentVideoState;
        if (currentVideoState === 'playing') {
            Alpine.store('doStore').selectObject(null);
            document.querySelector('#btnCreateObject').disabled = true;
            document.querySelector('#btnStartTracking').disabled = true;
            document.querySelector('#btnPauseTracking').disabled = true;
            document.querySelector('#btnEndObject').disabled = true;
        }
        if (currentVideoState === 'paused') {
            document.querySelector('#btnCreateObject').disabled = false;
        }
    })
    Alpine.effect(async () => {
        const newObjectState = Alpine.store('doStore').newObjectState;
        if (newObjectState === 'creating') {
            document.querySelector('#btnCreateObject').disabled = true;
            document.querySelector('#btnStartTracking').disabled = true;
            document.querySelector('#btnPauseTracking').disabled = true;
            document.querySelector('#btnEndObject').disabled = true;
            annotation.video.disablePlayPause();
        }
        if (newObjectState === 'created') {
            await objectManager.createdObject();
        }
        if (newObjectState === 'editing') {
            document.querySelector('#btnCreateObject').disabled = true;
            document.querySelector('#btnStartTracking').disabled = false;
            document.querySelector('#btnPauseTracking').disabled = true;
            document.querySelector('#btnEndObject').disabled = false;
            annotation.video.disablePlayPause();
        }
        if (newObjectState === 'tracking') {
            document.querySelector('#btnCreateObject').disabled = true;
            document.querySelector('#btnStartTracking').disabled = true;
            document.querySelector('#btnPauseTracking').disabled = false;
            document.querySelector('#btnEndObject').disabled = true;
            annotation.video.disablePlayPause();
        }
        if (newObjectState === 'none') {
            document.querySelector('#btnCreateObject').disabled = false;
            document.querySelector('#btnStartTracking').disabled = true;
            document.querySelector('#btnPauseTracking').disabled = true;
            document.querySelector('#btnEndObject').disabled = true;
            annotation.video.enablePlayPause();
        }
    })
    Alpine.effect(async () => {
        const dataState = Alpine.store('doStore').dataState;
        if (dataState === 'loading') {
            if (!$('#gridObjects').length)  {
                $('#gridObjects').datagrid('loading');
            }
        }
        if (dataState === 'loaded') {
            console.log('Data Loaded');
            window.annotation.objects.annotateObjects(annotation.objectList);
            $('#gridObjects').datagrid({
                data: annotation.objectList
            });
            $('#gridObjects').datagrid('loaded');

        }
    })
})
