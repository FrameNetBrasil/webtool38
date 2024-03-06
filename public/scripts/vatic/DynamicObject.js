"use strict";

/**
 * Represents an object bounding boxes throughout the entire frame sequence.
 */
class DynamicObject {
    constructor(object) {
        this.object = object;
        if (object === null) {
            // new Object
            this.idObject = 0;
            this.color = vatic.getColor(1);
        } else {
            this.idObject = parseInt(object.order);
            this.color = vatic.getColor(object.order);
        }
        this.visible = true;
        this.hidden = false;
        this.locked = false;
        this.dom = null;
        this.frames = [];

        // this.chkObject = 0;
        // this.idObject = -1;
        // this.idObjectMM = -1;
        // this.frames = [];
        // this.boxes = [];
        // this.name = '';
        // this.idFrame = -1;
        // this.idFE = -1;
        // this.idLU = -1;
        // this.color = '#D3D3D3';
        // this.startFrame = -1;
        // this.endFrame = -1;
        // this.startTimestamp = -1;
        // this.endTimestamp = -1;
        // this.frame = '';
        // this.fe = '';
        // this.lu = '';
        // this.origin = '2';
        // this.state = 'clean';
        // this.startWord = -1;
        // this.endWord = -1;
        // this.idForNewBox = 0;
    }

    inFrame(frameNumber) {
        return (this.object.startFrame <= frameNumber) && (this.object.endFrame >= frameNumber);
    }


    getFrameAt(frameNumber) {
        for (let i = 0; i < this.frames.length; i++) {
            let currentFrame = this.frames[i];
            if (currentFrame.frameNumber > frameNumber) {
                break;
            }
            if (currentFrame.frameNumber === frameNumber) {
                return currentFrame;
            }
        }
        return null;
    }

    drawBoxInFrame(frameNumber) {
        this.dom.style.display = 'none';
        let frameObject = this.getFrameAt(frameNumber);
        if (frameObject) {
            if (!this.hidden) {
                if (frameObject.isVisible()) {
                    let bbox = frameObject.bbox;
                    this.dom.style.position = 'absolute';
                    this.dom.style.display = 'block';
                    this.dom.style.width = bbox.width + 'px';
                    this.dom.style.height = bbox.height + 'px';
                    this.dom.style.left = bbox.x + 'px';
                    this.dom.style.top = bbox.y + 'px';
                    this.dom.style.borderStyle = 'dotted';
                    this.dom.style.borderColor = this.color;
                    this.dom.style.borderWidth = "medium";
                    this.dom.style.backgroundColor = 'transparent';
                    this.dom.style.opacity = 1;
                    this.visible = true;
                    if (frameObject.blocked) {
                        this.dom.style.opacity = 0.5;
                        this.dom.style.backgroundColor = 'white';
                        this.dom.style.borderStyle = 'dashed';
                    }
                } else {
                    this.dom.style.display = 'none';
                    this.visible = false;
                }
            }
        }

    }

    /*
    loadFromDb(i, object) {
        // console.log(i,object);
        this.idObject = parseInt(object.idObject);//i;
        this.idObjectMM = parseInt(object.idObjectMM);
        this.idObjectSentenceMM = parseInt(object.idObjectSentenceMM);
        this.name = object.name;
        this.idFrame = object.idFrame ? parseInt(object.idFrame) : null;
        this.frame = object.frame;
        this.idFE = object.idFE ? parseInt(object.idFE) : null;
        this.idLU = object.idLU ? parseInt(object.idLU) : null;
        this.fe = object.fe;
        if (this.frame !== '') {
            this.frameFe = object.frame + '.' + object.fe;
        } else {
            this.frameFe = '';
        }
        this.lu = object.lu;
        //this.color = object.idFE ? '#' + object.color : '#D3D3D3';
        this.color = vatic.getColor(i);
        this.hidden = false;
        this.locked = false;
        this.origin = object.origin;
        this.startFrame = parseInt(object.startFrame);
        this.endFrame = parseInt(object.endFrame);
        this.startTimestamp = object.startTime;
        this.endTimestamp = object.endTime;
        this.start = this.startFrame + ' [' + object.startTime + 's]'
        this.end = this.endFrame + ' [' + object.endTime + 's]'
        this.state = 'clean';
        this.startWord = parseInt(object.startWord);
        this.endWord = parseInt(object.endWord);
    }



    cloneFrom(sourceObject) {
        // this.idObject is unique
        this.idObjectMM = null;
        this.name = sourceObject.name;
        this.idFrame = -1;
        this.frame = '';
        this.idFE = -1;
        this.fe = '';
        this.idLU = -1;
        this.lu = '';
        this.color = sourceObject.color;
        this.hidden = sourceObject.hidden;
        this.locked = sourceObject.locked;
        this.origin = sourceObject.origin;
        this.startFrame = sourceObject.startFrame;
        this.endFrame = sourceObject.endFrame;
        this.startTimestamp = sourceObject.startTimestamp;
        this.endTimestamp = sourceObject.endTimestamp;
        this.state = sourceObject.state;
        this.startWord = sourceObject.startWord;
        this.endWord = sourceObject.endWord;
        this.dom = sourceObject.dom;
        this.frames = [];
        for (var frame of sourceObject.frames) {
            this.frames.push(frame);
        }
    }

    setState(state) {
        this.state = state;
    }

    isVisible() {
        return this.visible;
    }

    addBox(annotatedBox) {
        for (let i = 0; i < this.boxes.length; i++) {
            if (this.boxes[i].id === annotatedBox.id) {
                this.boxes[i] = annotatedBox;
                return;
            }
        }
        if (annotatedBox.id === -1) {
            this.idForNewBox = this.idForNewBox - 1;
            annotatedBox.id = (-100 * this.idObject) + this.idForNewBox;
        }
        this.boxes.push(annotatedBox);
    }

    deleteBox(annotatedBox) {
        for (let i = 0; i < this.boxes.length; i++) {
            let currentBox = this.boxes[i];
            if (currentBox.id === annotatedBox.id) {
                this.boxes.splice(i, 1);
                return;
            }
        }
    }
    */

    addToFrame(frameObject) {
        // console.log('annotatedFrame', annotatedFrame);
        // console.debug('adding frame in annotated object ' + this.idObject);
        // console.log(this.frames.length + '  frame number = ' + frame.frameNumber);
        for (let i = 0; i < this.frames.length; i++) {
            if (this.frames[i].frameNumber === frameObject.frameNumber) {
                this.frames[i] = frameObject;
                // console.log(i, annotatedFrame);
                this.removeFromFrameToBeRecomputedFrom(i + 1);
                return;
            } else if (this.frames[i].frameNumber > frameObject.frameNumber) {
                this.frames.splice(i, 0, frameObject);
                this.removeFromFrameToBeRecomputedFrom(i + 1);
                this.injectInvisibleFrameAtOrigin();
                return;
            }
        }
        this.frames.push(frameObject);
        this.injectInvisibleFrameAtOrigin();
    }

    /*
    get(frameNumber) {
        // Verifica se este AnnotatedObject existe no frame frameNumber
        for (let i = 0; i < this.frames.length; i++) {
            let currentFrame = this.frames[i];
            if (currentFrame.frameNumber > frameNumber) {
                break;
            }

            if (currentFrame.frameNumber === frameNumber) {
                return currentFrame;
            }
        }
        return null;
    }



    removeFrame(frameNumber) {
        for (let i = frameNumber; i < this.frames.length; i++) {
            let currentFrame = this.frames[i];
            if (currentFrame.frameNumber === frameNumber) {
                this.frames.splice(i, 1);
                return;
            }
        }
    }

    */
    removeFromFrameToBeRecomputedFrom(frameNumber) {
        let count = 0;
        for (let i = frameNumber; i < this.frames.length; i++) {
            if (this.frames[i].isGroundTruth) {
                break;
            }
            count++;
        }
        if (count > 0) {
            this.frames.splice(frameNumber, count);
        }
    }

    injectInvisibleFrameAtOrigin() {
        if (this.frames.length === 0 || this.frames[0].frameNumber > 0) {
            let frameObject = new Frame(0, null, false);
            this.frames.splice(0, 0, frameObject);
        }
    }
}
