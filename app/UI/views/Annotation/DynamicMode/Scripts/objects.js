annotation.objects = {
    tracker: null,
    boxesContainer: document.querySelector('#boxesContainer'),
    init: () => {
        console.log('initing objectManager');
        annotation.objects.tracker = new ObjectsTracker();
    },
    config: (config) => {
        annotation.objects.tracker.config(config);
    },

    add: (annotatedObject) => {
        annotation.objects.tracker.add(annotatedObject);
    },
    /*
    push: (annotatedObject) => {
        dynamicObjects.tracker.add(annotatedObject);
    },
    */
    get: (idObject) => {
        return annotation.objects.tracker.annotatedObjects.find(o => o.idObject === idObject);
    },
    getByIdObjectMM: (idObjectMM) => {
        return annotation.objects.tracker.annotatedObjects.find(o => o.object.idObjectMM === idObjectMM);
    },
    /*
    clear: (annotatedObject) => {
        dynamicObjects.tracker.clear(annotatedObject);
    },
    */
    clearAll: () => {
        annotation.objects.tracker.clearAll();
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
        //bbox.addClass('bbox');
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
                    d.left = 0;
                }
                if (d.top < 0) {
                    d.top = 0;
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
        bbox.css('display', 'none');
    },
    newBboxElement: () => {
        let dom = document.createElement('div');
        dom.className = 'bbox';
        annotation.objects.boxesContainer.appendChild(dom);
        return dom;
    },

    annotateObjects: (objects) => {
        annotation.objects.clearAll();
        for (var object of objects) {
            if ((object.startFrame >= annotation.video.framesRange.first) && (object.startFrame <= annotation.video.framesRange.last)) {
                let annotatedObject = new DynamicObject(object);
                annotatedObject.dom = annotation.objects.newBboxElement();
                annotation.objects.add(annotatedObject);
                annotation.objects.interactify(
                    annotatedObject,
                    (x, y, width, height) => {
                        let bbox = new BoundingBox(x, y, width, height);
                        let currentFrame = Alpine.store('doStore').currentFrame;
                        //let frameObject = new Frame(currentFrame, bbox, true, idDynamicBBoxMM);
                        let frameObject = annotatedObject.getFrameAt(currentFrame);
                        frameObject.bbox = bbox;
                        frameObject.isGroundTruth = true;
                        annotatedObject.addToFrame(frameObject);
                        console.log(frameObject.idDynamicBBoxMM, bbox)
                        //annotation.objects.saveRawObject(annotatedObject);
                        annotation.api.updateBBox({
                            idDynamicBBoxMM: frameObject.idDynamicBBoxMM,
                            bbox: bbox
                        });
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
                    let idDynamicBBoxMM = parseInt(polygon.idDynamicBBoxMM);
                    let frameObject = new Frame(frameNumber, bbox, isGroundThrough, idDynamicBBoxMM);
                    frameObject.blocked = (parseInt(polygon.blocked) === 1);
                    annotatedObject.addToFrame(frameObject);
                    lastFrame = frameNumber;
                }
            }
        }
        console.log('objects annotated');
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
            let newObjectState = Alpine.store('doStore').newObjectState;
            // apaga todas as boxes
            $('.bbox').css("display", "none");
            let currentObject = Alpine.store('doStore').currentObject;
            console.log('drawFrame ' + frameNumber + ' ' + newObjectState);
            if (currentObject) {
                let isTracking = (newObjectState === 'tracking');
                if (isTracking) {
                    // se está editando, a box
                    // - ou já existe (foi criada antes)
                    // - ou precisa ser criada
                    // em ambos os casos, passa os parâmetros para o tracker e deixa ele resolver

                    let tracker = annotation.objects.tracker;
                    tracker.getFrameWithObject(frameNumber, currentObject)
                        .then((frameWithObjects) => {
                            console.log('frameWithObject', frameWithObjects);
                            console.log('frameNumber', frameNumber);
                            currentObject.drawBoxInFrame(frameNumber, 'tracking');
                        });
                    //that.$store.commit('redrawFrame', false);
                } else {
                    console.log('drawFrame not tracking', currentObject);
                    currentObject.drawBoxInFrame(frameNumber, 'showing');
                }
            }
        } catch (e) {
            manager.messager('error', e.message);
        }
    },
    drawFrameBoxes: function (frameNumber) {
        // show/hide todas as boxes existentes no frame frameNumber
        //let that = this;
        frameNumber = parseInt(frameNumber);
        if (frameNumber < 1) {
            return;
        }
        let state = Alpine.store('doStore').showHideBoxesState;
        if (state === 'hide') {
            $('.bbox').css("display", "none");
        } else {
            let objects = annotation.objects.tracker.annotatedObjects.filter(o => o.inFrame(frameNumber));
            console.log(objects);
            objects.forEach(o => {
                o.drawBoxInFrame(frameNumber, 'showing');
            })
        }
    },
    creatingObject() {
        annotation.drawBox.init();
        console.log("creating new object")
        document.querySelector('#canvas').style.cursor = 'crosshair';
        $("#canvas").on('mousedown', function (e) {
            annotation.drawBox.handleMouseDown(e);
        });
        $("#canvas").on('mousemove', function (e) {
            annotation.drawBox.handleMouseMove(e);
        });
        $("#canvas").on('mouseup', function (e) {
            annotation.drawBox.handleMouseUp(e);
        });
        $("#canvas").on('mouseout', function (e) {
            annotation.drawBox.handleMouseOut(e);
        });
    },
    async createdObject() {
        console.log("new box created")
        document.querySelector('#canvas').style.cursor = 'default';
        $("#canvas").off('mousedown');
        $("#canvas").off('mousemove');
        $("#canvas").off('mouseup');
        $("#canvas").off('mouseout');
        console.log(annotation.drawBox.box);
        let tempObject = {
            bbox: new BoundingBox(annotation.drawBox.box.x, annotation.drawBox.box.y, annotation.drawBox.box.width, annotation.drawBox.box.height),
            dom: annotation.objects.newBboxElement()
        };
        let data = await annotation.objects.createNewObject(tempObject);
        console.log('after createNewObject');
    },
    initializeNewObject: (annotatedObject, currentFrame) => {
        //console.log(annotatedObject);
        annotatedObject.object = {
            idFrame: -1,
            frame: '',
            idFE: -1,
            fe: '',
            startFrame: currentFrame,
            //endFrame: annotation.video.framesRange.last
            endFrame: currentFrame
        };
        annotatedObject.visible = true;
        annotatedObject.hidden = false;
        annotatedObject.locked = false;
        annotatedObject.color = 'white';
    },
    createNewObject: async (tempObject) => {
        try {
            let currentFrame = Alpine.store('doStore').currentFrame;
            if (currentFrame === 0) {
                currentFrame = 1;
            }
            console.log('createNewObject', tempObject, currentFrame);
            let annotatedObject = new DynamicObject(null);
            annotatedObject.dom = tempObject.dom;
            let frameObject = new Frame(currentFrame, tempObject.bbox, true, null);
            annotatedObject.addToFrame(frameObject);
            annotation.objects.initializeNewObject(annotatedObject, currentFrame);
            annotation.objects.interactify(
                annotatedObject,
                (x, y, width, height, idDynamicBBoxMM) => {
                    let bbox = new BoundingBox(x, y, width, height);
                    let currentFrame = Alpine.store('doStore').currentFrame;
                    let frameObject = new Frame(currentFrame, bbox, true, idDynamicBBoxMM);
                    annotatedObject.addToFrame(frameObject);
                    annotation.objects.saveRawObject(annotatedObject);
                }
            );
            console.log('##### creating newObject');
            let params = {
                idObjectMM: null,
                idDynamicObjectMM: null,
                startFrame: annotatedObject.object.startFrame,
                endFrame: annotatedObject.object.endFrame,
                idFrame: null,
                idFrameElement: null,
                idLU: null,
                startTime: annotation.video.timeFromFrame(annotatedObject.object.startFrame),
                endTime: annotation.video.timeFromFrame(annotatedObject.object.endFrame),
                origin: 2,
                frames: [],
            }
            let data = await annotation.objects.saveObject(annotatedObject, params);
            Alpine.store('doStore').selectObjectByIdObjectMM(data.idDynamicObjectMM);

            //Alpine.store('doStore').newObjectState = 'tracking';
            manager.messager("success", "New object created.");
            return data;
        } catch (e) {
            Alpine.store('doStore').newObjectState = 'none';
            Alpine.store('doStore').currentVideoState = 'paused';
            manager.messager('error', e.message);
            return null;
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
                        frameTime: annotation.video.timeFromFrame(frame.frameNumber),
                        x: frame.bbox.x,
                        y: frame.bbox.y,
                        width: frame.bbox.width,
                        height: frame.bbox.height,
                        blocked: frame.blocked ? 1 : 0,
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

    saveObject: async (currentObject, params) => {
        params.idDocumentMM = annotation.documentMM.idDocumentMM;
        console.log('saveObject', currentObject, params)
        if (params.startFrame > params.endFrame) {
            throw new Error('endFrame must be greater or equal to startFrame.');
        }
        if (params.endFrame > currentObject.object.endFrame) {
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
                let frameObject = new Frame(frameNumber, bbox, isGroundThrough, null);
                frameObject.blocked = (parseInt(polygon.blocked) === 1);
                currentObject.addToFrame(frameObject);
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
                let frameObject = new Frame(frameNumber, bbox, isGroundThrough, null);
                frameObject.blocked = (parseInt(polygon.blocked) === 1);
                currentObject.add(frameObject);
            }
        }

        params.startTime = annotation.video.timeFromFrame(params.startFrame);
        params.endTime = annotation.video.timeFromFrame(params.endFrame);

        let frames = annotation.objects.getObjectFrameData(currentObject, params.startFrame, params.endFrame);
        console.log(frames);
        params.frames = frames.frames;

        let data = await annotation.api.updateObject(params);
        console.log('object updated', data);

        await Alpine.store('doStore').updateObjectList();
        return data;
    },
    saveRawObject: async (currentObject) => {
        try {
            console.log('saving raw object #', currentObject.idObject)
            let params = {
                idDocumentMM: annotation.documentMM.idDocumentMM,
                idObjectMM: currentObject.object.idObjectMM,
                idDynamicObjectMM: currentObject.object.idObjectMM,
                startFrame: currentObject.object.startFrame,
                endFrame: currentObject.object.endFrame,
                idFrame: currentObject.object.idFrame,
                idFrameElement: currentObject.object.idFE,
                idLU: currentObject.object.idLU,
                startTime: annotation.video.timeFromFrame(currentObject.object.startFrame),
                endTime: annotation.video.timeFromFrame(currentObject.object.endFrame),
                origin: 2,
                frames: [],
            }
            annotation.objects.saveObject(currentObject, params);
        } catch (e) {
            Alpine.store('doStore').newObjectState = 'none';
            Alpine.store('doStore').currentVideoState = 'paused';
            manager.messager('error', e.message);
            return null;
        }

    },
    async tracking(canGoOn) {
        if (canGoOn) {
            let currentFrame = Alpine.store('doStore').currentFrame;
            if (((currentFrame >= annotation.video.framesRange.first) && (currentFrame < annotation.video.framesRange.last))) {
                currentFrame = currentFrame + 1;
                console.log('tracking....', currentFrame);
                annotation.video.gotoFrame(currentFrame);
                Alpine.store('doStore').updateCurrentFrame(currentFrame);
                await new Promise(r => setTimeout(r, 1000));
                return annotation.objects.tracking(Alpine.store('doStore').currentVideoState === 'playing');
            }
        }
    },
    async cloneObject(idObject) {
        let sourceObject = annotation.objects.get(idObject)
        let cloneObject = new DynamicObject(sourceObject.object);
        cloneObject.cloneFrom(sourceObject);
        let params = {
            idDocumentMM: annotation.documentMM.idDocumentMM,
            idObjectMM: null,
            idDynamicObjectMM: null,
            startFrame: cloneObject.object.startFrame,
            endFrame: cloneObject.object.endFrame,
            idFrame: null,
            idFrameElement: null,
            idLU: null,
            startTime: annotation.video.timeFromFrame(cloneObject.object.startFrame),
            endTime: annotation.video.timeFromFrame(cloneObject.object.endFrame),
            origin: 2,
            frames: cloneObject.object.frames,
        }
        let data = await annotation.objects.saveObject(cloneObject, params);
        Alpine.store('doStore').selectObjectByIdObjectMM(data.idDynamicObjectMM);
        manager.messager("success", "Object cloned.");
    }
}
