<script type="text/javascript">

</script>

<div class="controls flex flex-row gap-3">
    <div>
        <button
            type="button"
            id="btnCreateObject"
            class="btn btn-control"
            x-data @click="$store.doStore.createObject()"
        >
            <i class="material-icons-outlined wt-icon wt-icon-create-object"></i>
            <span class="label">Create Object</span>
        </button>
    </div>
    <div>
        <button
            type="button"
            id="btnStartTracking"
            class="btn btn-control"
            disabled
            x-data @click="$store.doStore.startTracking()"
        >
            <i class="material-icons-outlined wt-icon wt-icon-start-tracking"></i>
            <span class="label">Start Tracking</span>
        </button>
    </div>
    <div>
        <button
            type="button"
            id="btnPauseTracking"
            class="btn btn-control"
            disabled
            x-data @click="$store.doStore.pauseTracking()"
        >
            <i class="material-icons-outlined wt-icon wt-icon-pause-tracking"></i>
            <span class="label">Pause Tracking</span>
        </button>
    </div>
    <div>
        <button
            type="button"
            id="btnStopTracking"
            class="btn btn-control"
            disabled
            x-data @click="$store.doStore.stopTracking()"
        >
            <i class="material-icons-outlined wt-icon wt-icon-stop-tracking"></i>
            <span class="label">Stop Tracking</span>
        </button>
    </div>
    <div>
        <button
            type="button"
            id="btnEndObject"
            class="btn btn-control"
            disabled
            x-data @click="$store.doStore.endObject()"
        >
            <i class="material-icons-outlined wt-icon wt-icon-end-object"></i>
            <span class="label" x-data x-text="'End Object at frame #' + ($store.doStore.currentFrame || '')"></span>
        </button>
    </div>
</div>
<div class="controls flex flex-row gap-3">
    <div>
        <button
            type="button"
            id="btnShowHideObjects"
            class="btn btn-option"
            x-data @click="$store.doStore.showHideObjects()"
        >
            <i class="material-icons-outlined wt-icon wt-icon-show-hide-objects-on"></i>
            <span class="label" x-data x-text="'Show/Hide Objects at frame #' + ($store.doStore.currentFrame || '')"></span>
        </button>
    </div>
</div>

