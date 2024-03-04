window.objectManager = {
    tracker: null,
    boxesContainer: document.querySelector('#videoContainer'),
    tempObject: null,
    init: (config) => {
        console.log('initing objectManager');
        objectManager.tracker = new ObjectsTracker(config);
    },

    add: (annotatedObject) => {
        objectManager.tracker.add(annotatedObject);
    },
    /*
    push: (annotatedObject) => {
        dynamicObjects.tracker.add(annotatedObject);
    },
    */
    get: (idObject) => {
        return objectManager.tracker.annotatedObjects.find(o => o.idObject === idObject);
    },
    /*
    clear: (annotatedObject) => {
        dynamicObjects.tracker.clear(annotatedObject);
    },
    */
    clearAll: () => {
        objectManager.tracker.clearAll();
    },
    /*
    getByIdObjectMM: (idObjectMM) => {
        return dynamicObjects.tracker.annotatedObjects.find(o => o.idObjectMM === idObjectMM);
    },
    clearObject: (i) => {
        let annotatedObject = dynamicObjects.tracker.get(i);
        dynamicObjects.tracker.clear(annotatedObject);
    },
    toAbsoluteCoord: (x, y, width, height, currentScale) => {
        return {
            x: Math.round(x / currentScale),
            y: Math.round(y / currentScale),
            width: Math.round(width / currentScale),
            height: Math.round(height / currentScale)
        }
    },
    toScaledCoord: (x, y, width, height, currentScale) => {
        return {
            x: Math.round(x * currentScale),
            y: Math.round(y * currentScale),
            width: Math.round(width * currentScale),
            height: Math.round(height * currentScale)
        }
    },
    */
    interactify: (annotatedObject, onChange) => {
        let dom = annotatedObject.dom;
        let bbox = $(dom);
        bbox.addClass('bbox');
        let createHandleDiv = (className, content = null) => {
            //console.log('className = ' + className + '  content = ' + content);
            let handle = document.createElement('div');
            handle.className = className;
            bbox.append(handle);
            if (content !== null) {
                handle.innerHTML = content;
            }
            return handle;
        };
        let x = createHandleDiv('handle center-drag');
        let i = createHandleDiv('objectId', annotatedObject.idObject);
        bbox.resizable({
            handles: "n, e, s, w",
            onStopResize: (e) => {
                let position = bbox.position();
                onChange(Math.round(position.left), Math.round(position.top), Math.round(bbox.width()), Math.round(bbox.height()));
            }
        });
        i.addEventListener("click", function () {
            //dynamicStore.dispatch('selectObject', parseInt(this.innerHTML))
        });
        bbox.draggable({
            handle: $(x),
            onDrag: (e) => {
                var d = e.data;
                if (d.left < 0) {
                    d.left = 0
                }
                if (d.top < 0) {
                    d.top = 0
                }
                if (d.left + $(d.target).outerWidth() > $(d.parent).width()) {
                    d.left = $(d.parent).width() - $(d.target).outerWidth();
                }
                if (d.top + $(d.target).outerHeight() > $(d.parent).height()) {
                    d.top = $(d.parent).height() - $(d.target).outerHeight();
                }
            },
            onStopDrag: (e) => {
                let position = bbox.position();
                onChange(Math.round(position.left), Math.round(position.top), Math.round(bbox.width()), Math.round(bbox.height()));
            }
        });
        bbox.css('display', 'none')
    },
    newBboxElement: () => {
        let dom = document.createElement('div');
        dom.className = 'bbox';
        objectManager.boxesContainer.appendChild(dom);
        //dom.style.display = 'none';
        return dom;
    },

    annotateObjects: (objects) => {
        objectManager.clearAll();
        // let boxesContainer = annotationVideoModel.boxesContainer;
        // let currentScale = annotationVideoModel.currentScale;
        // let objectsLoaded = await annotationVideoModel.api.loadObjects();
        // console.log(objectsLoaded);
        //let i = 1;
        for (var object of objects) {
            //console.log(object,annotationVideo.framesRange);
            if ((object.startFrame >= annotationVideo.framesRange.first)
                && (object.startFrame <= annotationVideo.framesRange.last)) {
                let annotatedObject = new DynamicObject(object);
                annotatedObject.dom = objectManager.newBboxElement();
                objectManager.add(annotatedObject);
                objectManager.interactify(
                    annotatedObject,
                    (x, y, width, height) => {
                        //let currentScale = annotationVideoModel.currentScale;
                        //console.log(x,y,width,height)
                        //let absolute = dynamicObjects.toAbsoluteCoord(x, y, width, height, currentScale);
                        //console.log(absolute);
                        //let bbox = new BoundingBox(absolute.x, absolute.y, absolute.width, absolute.height);
                        let bbox = new BoundingBox(x, y, width, height);
                        let currentFrame = Alpine.store('doStore').currentFrame;
                        let frameObject = new Frame(currentFrame, bbox, true);
                        annotatedObject.addToFrame(frameObject);
                        //console.log('box changed!', annotatedObject.idObject);
                        objectManager.saveRawObject(annotatedObject);
                        // this.$store.dispatch("setObjectState", {
                        //     object: annotatedObject,
                        //     state: 'dirty',
                        //     flag: this.currentFrame
                        // });
                    }
                );
                let lastFrame = -1;
                let bbox = null;
                let polygons = object.bboxes;
                for (let j = 0; j < polygons.length; j++) {
                    let polygon = object.bboxes[j];
                    let frameNumber = parseInt(polygon.frameNumber);
                    let isGroundThrough = true;// parseInt(topLeft.find('l').text()) == 1;
                    let x = parseInt(polygon.x);
                    let y = parseInt(polygon.y);
                    let w = parseInt(polygon.width);
                    let h = parseInt(polygon.height);
                    bbox = new BoundingBox(x, y, w, h);
                    let frameObject = new Frame(frameNumber, bbox, isGroundThrough);
                    frameObject.blocked = (parseInt(polygon.blocked) === 1);
                    annotatedObject.addToFrame(frameObject);
                    lastFrame = frameNumber;
                }
            }
        }
        console.log('objects annotated');
        //return objectsLoaded;
        // dynamicStore.commit('objects', objectsLoaded)
        // dynamicStore.commit('updateGridPane', true)
    },
    async creatingObject(event) {
        if (objectManager.tempObject !== null) {
            console.log('mouse click - create new object')
            let data = await objectManager.createNewObject(objectManager.tempObject);
            console.log('after createNewObject')
            document.querySelector('#' + annotation.idVideo).style.cursor = 'default';
            objectManager.tempObject = null;
        } else {
            let dom = objectManager.newBboxElement();
            dom.style.left = event.x + 'px';
            dom.style.top = event.y + 'px';
            dom.style.borderColor = '#D3D3D3';
            objectManager.tempObject = {
                dom: dom
            };
        }
    },

    initializeNewObject: (annotatedObject, currentFrame) => {
        //console.log(annotatedObject);
        annotatedObject.object = {
            idFrame: -1,
            frame: '',
            idFE: -1,
            fe: '',
            startFrame: currentFrame,
            endFrame: annotationVideo.framesRange.last
        }
        annotatedObject.visible = true;
        annotatedObject.hidden = false;
        annotatedObject.locked = false;
        annotatedObject.color = 'white';
    },

    createNewObject: async (tempObject) => {
        let currentFrame = Alpine.store('doStore').currentFrame;
        if (currentFrame === 0) {
            currentFrame = 1;
        }
        console.log('createNewObject',tempObject,currentFrame);
        let annotatedObject = new DynamicObject(null);
        annotatedObject.dom = tempObject.dom;
        let bbox = new BoundingBox(tempObject.x, tempObject.y, tempObject.width, tempObject.height);
        let frameObject = new Frame(currentFrame, bbox, true);
        annotatedObject.addToFrame(frameObject);
        objectManager.initializeNewObject(annotatedObject, currentFrame);
        objectManager.interactify(
            annotatedObject,
            (x, y, width, height) => {
                let currentObject = annotatedObject;//this.$store.state.currentObject;
                if (!currentObject) {
                    return;
                }
                console.log('interactify fn', currentObject.idObject)
                if (annotatedObject.idObject !== currentObject.idObject) {
                    return;
                }
                let absolute = dynamicObjects.toAbsoluteCoord(x, y, width, height, currentScale);
                let bbox = new BoundingBox(absolute.x, absolute.y, absolute.width, absolute.height);
                let currentFrame = dynamicStore.state.currentFrame;
                annotatedObject.add(new Frame(currentFrame, bbox, true));
                console.log('box changed!', annotatedObject.idObject, absolute.x + absolute.y + absolute.width + absolute.height);
                dynamicObjects.saveRawObject(annotatedObject);
                // this.$store.dispatch("setObjectState", {
                //     object: annotatedObject,
                //     state: 'dirty',
                //     flag: absolute.x + absolute.y + absolute.width + absolute.height
                // });
            }
        );
        // annotatedObject.startFrame = currentFrame;
        // annotatedObject.endFrame = annotationVideoModel.framesRange.last;
        console.log('##### creating newObject');
        let data = await dynamicObjects.saveObject(annotatedObject,{
            idDocumentMM: annotationVideoModel.documentMM.idDocumentMM,
            idObjectMM: null,
            startFrame: annotatedObject.startFrame,
            endFrame: annotatedObject.endFrame,
            idFrame:null,
            idFrameElement: null,
            idLU: null,
            startTime: (annotatedObject.startFrame - 1) / annotationVideoModel.fps,
            endTime:(annotatedObject.endFrame - 1) / annotationVideoModel.fps,
        })
        $.messager.alert('Ok', "New object created.", 'info');
        //dynamicStore.commit('currentState', 'objectEditing')
//        dynamicStore.dispatch('selectObjectMM', data.idObjectMM);
        return data;
    },
    clearFrameObject: function () {
        $('.bbox').css("display", "none");
    },
    drawFrameObject: function (frameNumber) {
        // desenha a box do objeto atual correspondente ao frame indicado por frameNumber
        //let that = this;
        frameNumber = parseInt(frameNumber);
        if (frameNumber < 1) {
            return;
        }
        try {
            let currentObjectState = Alpine.store('doStore').currentObjectState;
            // apaga todas as boxes
            $('.bbox').css("display", "none");
            let currentObject = Alpine.store('doStore').currentObject;
            console.log('drawFrame ' + frameNumber + ' ' + currentObjectState);
            if (currentObject) {
                let isEditing = ((currentObjectState === 'objectEditing') || (currentObjectState === 'objectTracking') || (currentObjectState === 'objectPaused'));
                if (isEditing) {
                    // se está editando, a box
                    // - ou já existe (foi criada antes)
                    // - ou precisa ser criada
                    // em ambos os casos, passa os parâmetros para o tracker e deixa ele resolver
                    let tracker = objectManager.tracker;
                    tracker.getFrameWithObject(frameNumber, currentObject)
                        .then(() => {
                            currentObject.drawBoxInFrame(frameNumber);
                        });
                    //that.$store.commit('redrawFrame', false);
                } else {
                    console.log('drawFrame not editing', currentObject);
                    currentObject.drawBoxInFrame(frameNumber);
                }
            }
        } catch (e) {
            manager.messager('error', e.message);
        }
    },
    getObjectFrameData: (currentObject, startFrame, endFrame) => {
        console.log('getObjectFrameData', currentObject, startFrame, endFrame)
        let data = [];
        //let lastFrame = currentObject.endFrame;
        let lastFrame = startFrame;
        for (frame of currentObject.frames) {
            if ((frame.frameNumber >= startFrame) && (frame.frameNumber <= endFrame)) {
                if (frame.bbox !== null) {
                    data.push({
                        frameNumber: frame.frameNumber,
                        frameTime: (frame.frameNumber - 1) / annotationVideo.fps,
                        x: frame.bbox.x,
                        y: frame.bbox.y,
                        width: frame.bbox.width,
                        height: frame.bbox.height,
                        blocked: frame.blocked,
                    })
                    lastFrame = frame.frameNumber;
                }
            }
        }
        return {
            frames: data,
            lastFrame: lastFrame
        }
    },
    /*
    saveObject: async (currentObject, params) => {
        console.log('##### saving newObject');
        console.log('saveObject', currentObject,params)

        //  params = {
        //     idObjectMM
        //     idDocumentMM
        //     startFrame
        //     endFrame
        //     idFrame
        //     frame
        //     idFrameElement
        //     fe
        //     idLU
        //     lu
        //     startTime
        //     endTime
        // }

        if (params.startFrame > params.endFrame) {
            throw new Error('endFrame must be greater or equal to startFrame.');
        }

        if (params.endFrame > currentObject.endFrame) {
            let bbox = null;
            let j = currentObject.frames.length - 1;
            let polygon = currentObject.frames[j];
            for (let i = currentObject.endFrame; i <= params.endFrame; i++) {
                let frameNumber = i;
                let isGroundThrough = true;
                let x = parseInt(polygon.bbox.x);
                let y = parseInt(polygon.bbox.y);
                let w = parseInt(polygon.bbox.width);
                let h = parseInt(polygon.bbox.height);
                bbox = new BoundingBox(x, y, w, h);
                let annotatedFrame = new Frame(frameNumber, bbox, isGroundThrough);
                annotatedFrame.blocked = (parseInt(polygon.blocked) === 1);
                currentObject.add(annotatedFrame);
            }
        }

        if (params.startFrame < currentObject.startFrame) {
            let bbox = null;
            let polygon = currentObject.get(currentObject.startFrame);
            console.log(polygon);
            for (let i = params.startFrame; i < currentObject.startFrame; i++) {
                let frameNumber = i;
                let isGroundThrough = true;
                let x = parseInt(polygon.bbox.x);
                let y = parseInt(polygon.bbox.y);
                let w = parseInt(polygon.bbox.width);
                let h = parseInt(polygon.bbox.height);
                bbox = new BoundingBox(x, y, w, h);
                let annotatedFrame = new Frame(frameNumber, bbox, isGroundThrough);
                annotatedFrame.blocked = (parseInt(polygon.blocked) === 1);
                currentObject.add(annotatedFrame);
            }
        }
        let frames = dynamicObjects.getObjectFrameData(currentObject, params.startFrame, params.endFrame);
        console.log(frames);
        params.frames = frames.frames;

        let data = await dynamicAPI.updateObject(params);
        console.log(data);
        annotationVideoModel.currentIdObjectMM = data.idObjectMM;
        dynamicStore.commit('updateGridPane', true)
        return data;
    },
    saveObjectData: async (currentObject, params) => {
        console.log('saveObject', currentObject,params)

        //  params = {
        //     idObjectMM
        //     idDocumentMM
        //     startFrame
        //     endFrame
        //     idFrame
        //     frame
        //     idFrameElement
        //     fe
        //     idLU
        //     lu
        //     startTime
        //     endTime
        // }

        if (params.startFrame > params.endFrame) {
            throw new Error('endFrame must be greater or equal to startFrame.');
        }
        let data = await dynamicAPI.updateObjectData(params);
        console.log(data);
        annotationVideoModel.currentIdObjectMM = data.idObjectMM;
        dynamicStore.commit('updateGridPane', true)
        return data;
    },
    saveCurrentObject: async () => {
        let object = dynamicStore.state.currentObject;
        let currentObject = dynamicObjects.get(object.idObject);
        console.log('saving currentObject', currentObject)
        dynamicObjects.saveRawObject(currentObject)
    },
    */
    saveRawObject: async (currentObject) => {
        console.log('saving raw object #', currentObject.idObject)
        let params = {
            idObjectMM: currentObject.idObjectMM,
            idDocumentMM: annotation.documentMM.idDocumentMM,
            startFrame: currentObject.object.startFrame,
            endFrame: currentObject.object.endFrame,
            idFrame: currentObject.object.idFrame,
            idFrameElement: currentObject.object.idFE,
            idLU: currentObject.object.idLU,
            startTime: (currentObject.object.startFrame - 1) / annotationVideo.fps,
            endTime: (currentObject.object.endFrame - 1) / annotationVideo.fps,
        }
        let frames = this.getObjectFrameData(currentObject, params.startFrame, params.endFrame);
        params.frames = frames.frames;
        console.log('params', params);
        let data = await annotation.updateObject(params);
    },
    /*
    deleteObject: async(currentObject) => {
        let msg = 'Current Object: #' + currentObject.idObject + ' [' + currentObject.idObjectMM + '] deleted.';
        await dynamicAPI.deleteObjects([currentObject.idObjectMM]);
        annotationVideoModel.currentIdObjectMM = -1;
        dynamicStore.commit('updateGridPane', true)
        $.messager.alert('Ok', msg, 'info');
    }
    */

}
