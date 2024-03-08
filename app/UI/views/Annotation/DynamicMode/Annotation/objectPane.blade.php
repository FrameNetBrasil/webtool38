<div id="dynamicModeObject" x-data="$store.doStore">
    <template x-if="currentObject?.object?.order">
        <div class="wt-form">
            <div class="form-header">
                <div class="form-title" x-text="'Object #' + currentObject.object.order"></div>
            </div>
            <form>
                <div class="form-fields">
                    <x-hidden-field id="idDynamicObjectMM" value=""></x-hidden-field>
                    <div class="grid">
                        <div class="col-4">
                            <x-text-field id="startFrame" label="StartFrame" value=""></x-text-field>
                        </div>
                        <div class="col-4">
                            <x-text-field id="endFrame" label="EndFrame" value=""></x-text-field>
                        </div>
                    </div>
                    <div class="grid">
                        <div class="col-4">
                            <x-combobox.frame id="idFrame" label="Frame"
                                              onSelect="annotation.formObject.onChangeFrame"></x-combobox.frame>
                        </div>
                        <div id="feContainer" class="col-4">
                            @include("Annotation.DynamicMode.Annotation.objectFEPane")
                        </div>
                        <div class="col-4">
                            <x-combobox.lu id="idLU" label="LU"></x-combobox.lu>
                        </div>
                    </div>
                </div>
                <div class="form-buttons">
                    <x-submit label="Save" hx-post="/frame"></x-submit>
                </div>
            </form>
        </div>
    </template>
</div>
