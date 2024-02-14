<div style="display:flex; flex-direction: column; width:auto" style="padding: 8px">
    <form>
        <x-hidden-field
                id="idStaticSentenceMM"
                value="{{$data->idStaticSentenceMM}}"
        ></x-hidden-field>
        <div class="flex flex-row flex-wrap gap-2">
            <x-combobox.frame
                    id="idFrame"
                    label="Choose event frame:"
                    placeholder="Frame (min: 2 chars)"
                    style="width:250px"
                    class="mb-2"
            ></x-combobox.frame>
            <x-combobox.lu-event
                    id="idLU"
                    label="  or using event related LU:"
                    placeholder="LU (min: 2 chars)"
                    style="width:300px"
                    class="mb-2"
            ></x-combobox.lu-event>
            <div class="form-field">
                <div>&nbsp;</div>
                <x-button
                        id="btnSubmit"
                        label="Add Frame"
                        hx-target="#frameElementsPane"
                        hx-post="/annotation/staticFrameMode1/fes"
                ></x-button>
            </div>
        </div>

    </form>
</div>
<div id="frameElementsPane" class="pt-2">
    @include('Annotation.StaticFrameMode1.fes')
</div>
</div>

