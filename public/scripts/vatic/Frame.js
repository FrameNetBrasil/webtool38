"use strict";

/**
 * Represents a bounding box at a particular frame.
 */
class Frame {
    constructor(frameNumber, bbox, isGroundTruth, idDynamicBBoxMM) {
        this.frameNumber = frameNumber;
        this.bbox = bbox;
        this.isGroundTruth = isGroundTruth;
        this.blocked = false;
        this.idDynamicBBoxMM = idDynamicBBoxMM;
    }

    isVisible() {
        return this.bbox != null;
    }
}
