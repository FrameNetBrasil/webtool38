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
        showHideBoxesState: 'hide',
        init() {
            annotation.objects.init();
        },
        config() {
            let config = {
                idVideoDOMElement: annotation.video.idVideo,
                fps: annotation.video.fps,
            }
            annotation.objects.config(config);
            annotation.drawBox.config(config);
        },
        async updateObjectList() {
            this.dataState = 'loading';
            await annotation.api.loadObjects();
        },
        updateCurrentFrame(frameNumber) {
            console.log('updateCurrentFrame',this.currentVideoState,this.newObjectState);
            this.frameCount = this.currentFrame = frameNumber;
            if ((this.currentVideoState === 'paused')
                || (this.newObjectState === 'tracking')
                || (this.newObjectState === 'showing')
            ) {
                console.error('===================');
                annotation.objects.drawFrameObject(frameNumber);
            }
        },
        selectObject(idObject) {
            if (idObject === null) {
                this.currentObject = null;

                this.newObjectState = 'none';
            } else {
                let object = annotation.objects.get(idObject);
                this.currentObject = object;
                console.log(object);
                let time = annotation.video.timeFromFrame(object.object.startFrame);
                console.log(time, object.object.startFrame);
                annotation.video.player.currentTime(time);
                annotation.objects.drawFrameObject(object.object.startFrame);
                this.newObjectState = 'showing';
            }
            annotationGridObject.selectRowByObject(idObject);
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
            this.newObjectState = 'showing';
        },
        createObject() {
            if (this.currentVideoState === 'paused') {
                console.log('create object');
                this.newObjectState = 'creating';
                this.selectObject(null);
                annotation.objects.creatingObject();
            }
        },
        async endObject() {
            if (this.currentVideoState === 'paused') {
                console.log('end object');
                this.currentObject.object.endFrame = this.currentFrame;
                await annotation.objects.saveRawObject(this.currentObject)
                this.selectObject(null);
            }

            //     //this.$store.dispatch('endObject');
            //     // this.$store.commit('currentMode', 'video');
            //     this.$store.dispatch('currentObjectEndFrame', this.$store.state.currentFrame);
            //     console.log('ending frame = ', this.$store.state.currentObject.endFrame)
            //     this.$store.commit('currentState', 'videoPaused');
            //     dynamicObjects.saveCurrentObject();
            //     this.$store.commit('updateObjectPane', true);

        },
        async deleteObject(idObjectMM) {
            if (this.currentVideoState === 'paused') {
                await annotation.api.deleteObject(idObjectMM);
                this.updateObjectList();
                this.selectObject(null);
            }
        },

        startTracking() {
            console.log('start tracking');
            this.newObjectState = 'tracking';
            this.currentVideoState = 'playing';
            annotation.objects.tracking(true);

        },
        pauseTracking() {
            console.log('pause tracking');
            this.newObjectState = 'tracking';
            this.currentVideoState = 'paused';
        },
        stopTracking() {
            console.log('stop tracking');
            this.currentVideoState = 'paused';
            this.selectObject(null);
        },
        showHideObjects() {
            console.log('show/hide objects');
            if (this.showHideBoxesState === 'show') {
                this.showHideBoxesState = 'hide';
            } else {
                this.showHideBoxesState = 'show';
            }
            annotation.objects.drawFrameBoxes(this.currentFrame)
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
        const newObjectStateTracking = (Alpine.store('doStore').newObjectState === 'tracking');
        if (currentVideoState === 'playing') {
            if (!newObjectStateTracking) {
                document.querySelector('#btnCreateObject').disabled = true;
                document.querySelector('#btnStartTracking').disabled = true;
                document.querySelector('#btnPauseTracking').disabled = true;
                document.querySelector('#btnEndObject').disabled = true;
                document.querySelector('#btnShowHideObjects').disabled = true;
                let rate =  annotation.video.player.playbackRate();
                if (rate > 0.9) {
                    Alpine.store('doStore').newObjectState = 'none';
                }
            }
        }
        if (currentVideoState === 'paused') {
            if (!newObjectStateTracking) {
                document.querySelector('#btnCreateObject').disabled = false;
                document.querySelector('#btnShowHideObjects').disabled = false;
            }
        }
    })
    Alpine.effect(async () => {
        const newObjectState = Alpine.store('doStore').newObjectState;
        if (newObjectState === 'creating') {
            document.querySelector('#btnCreateObject').disabled = true;
            document.querySelector('#btnStartTracking').disabled = true;
            document.querySelector('#btnPauseTracking').disabled = true;
            document.querySelector('#btnStopTracking').disabled = true;
            document.querySelector('#btnEndObject').disabled = true;
            annotation.video.disablePlayPause();
        }
        if (newObjectState === 'created') {
            await annotation.objects.createdObject();
            Alpine.store('doStore').newObjectState = 'tracking';
            Alpine.store('doStore').currentVideoState = 'paused';
        }
        if (newObjectState === 'showing') {
            document.querySelector('#btnCreateObject').disabled = true;
            document.querySelector('#btnStartTracking').disabled = false;
            document.querySelector('#btnPauseTracking').disabled = true;
            document.querySelector('#btnStopTracking').disabled = true;
            document.querySelector('#btnEndObject').disabled = true;
            annotation.video.enablePlayPause();
        }
        if (newObjectState === 'tracking') {
            let pausedTracking = Alpine.store('doStore').currentVideoState === 'paused';
            document.querySelector('#btnCreateObject').disabled = true;
            document.querySelector('#btnStartTracking').disabled = !pausedTracking;
            document.querySelector('#btnPauseTracking').disabled = pausedTracking;
            document.querySelector('#btnStopTracking').disabled = !pausedTracking;
            document.querySelector('#btnEndObject').disabled = !pausedTracking;
            annotation.video.disablePlayPause();
        }
        if (newObjectState === 'none') {
            annotation.objects.clearFrameObject();
            document.querySelector('#btnCreateObject').disabled = false;
            document.querySelector('#btnStartTracking').disabled = true;
            document.querySelector('#btnPauseTracking').disabled = true;
            document.querySelector('#btnStopTracking').disabled = true;
            document.querySelector('#btnEndObject').disabled = true;
            annotation.video.enablePlayPause();
        }
    })
    Alpine.effect(async () => {
        const dataState = Alpine.store('doStore').dataState;
        if (dataState === 'loading') {
            if (!$('#gridObjects').length) {
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
            Alpine.store('doStore').newObjectState = 'none';
            Alpine.store('doStore').currentVideoState = 'paused';

        }
    })
})
