<div id="toolbarLayers">
    <div style="display:flex; flex-direction:row; justify-content:space-between">
        <div>
            <x-link-button id="btnRefresh" label="Refresh" icon="refresh" onclick="window.annotation.refresh()"></x-link-button>
            <x-link-button id="btnAddCxn" label="Add Cxn" icon="add" onclick="annotation.dlgCxnOpen()"></x-link-button>
            <x-link-button id="btnHelp" label="Help" icon="help" onclick="annotation.labelHelp()"></x-link-button>
            <x-link-button id="btnLayers" label="Manager Layers" icon="layers" onclick="annotationMethods.manageLayers()"></x-link-button>
        </div>
    </div>
</div>


@if($data->isMaster)
    <!--
    <div id="dlgUDTree" title="UD Tree" class="dlgUDTree" style="width:1000px;height:650px;padding:0px">
        <div id="dlgUDTree_tools">
            <a href='#' id="dlgUDTreeRefresh">
                Format
            </a>
            <a href='#' id="dlgUDTreeSave">
                Save
            </a>
            <a href='#' id="dlgUDTreeClose">
                Close
            </a>
        </div>
        <div id="UDTreeCanvas" class="jtk-demo-canvas canvas-wide statemachine-demo jtk-surface jtk-surface-nopan">
        </div>
    </div>
    -->
@endif

