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
        updateObjectList() {
            this.dataState = 'loading';
            window.annotation.loadObjects();
        },
        init() {
            let config = {
                idVideoDOMElement: annotation.idVideo,
                fps: annotation.fps,
            }
            window.objectManager.init(config);
            //this.updateObjectList();
        },
        updateCurrentFrame(frameNumber) {
            this.frameCount = this.currentFrame = frameNumber;
            if (this.currentVideoState === 'paused') {
                objectManager.drawFrameObject(frameNumber);
            }
        },
        selectObject(idObject) {
            if (idObject === null) {
                this.currentObject = null;
                objectManager.clearFrameObject();
            } else {
                let object = objectManager.get(idObject);
                this.currentObject = object;
                console.log(object);
                let time = annotationVideo.timeFromFrame(object.object.startFrame);
                console.log(time, object.object.startFrame);
                annotationVideo.player.currentTime(time);
                objectManager.drawFrameObject(object.object.startFrame);
            }
            annotationGridObject.selectRowByObject(idObject);
        },
        selectObjectFrame(idObject, frameNumber) {
            this.currentObject = objectManager.get(idObject);
            annotationGridObject.selectRowByObject(idObject);
            let time = annotationVideo.timeFromFrame(frameNumber);
            annotationVideo.player.currentTime(time);
            objectManager.drawFrameObject(frameNumber);
        },
        createObject() {
            if (this.currentVideoState === 'paused') {
                console.log('create object');
                document.querySelector('#btnCreateObject').disabled = true;
                document.querySelector('#btnStartTracking').disabled = false;
                document.querySelector('#btnPauseTracking').disabled = false;
                document.querySelector('#btnEndObject').disabled = false;
                this.selectObject(null);
                document.querySelector('#' + annotation.idVideo).style.cursor = 'crosshair';
                this.newObjectState = 'creating';
                this.currentVideoState = 'creating';
            }
        },
        endObject() {
            console.log('end object');
            document.querySelector('#btnCreateObject').disabled = false;
            document.querySelector('#btnStartTracking').disabled = true;
            document.querySelector('#btnPauseTracking').disabled = true;
            document.querySelector('#btnEndObject').disabled = true;
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

        },
        pauseTracking() {
            console.log('pause tracking');
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
            let objects = window.annotation.objects;
            objectManager.annotateObjects(objects);
            $('#gridObjects').datagrid({
                data: objects
            });
            $('#gridObjects').datagrid('loaded');

        }
    })
})
