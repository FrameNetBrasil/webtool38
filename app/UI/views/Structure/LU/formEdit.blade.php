<x-layout.content>
    <x-form id="lusFormEdit" title="Edit LU" center="true">
        <x-slot:fields>
            <x-hidden-field id="update_idLU" :value="$data->lu->idLU"></x-hidden-field>
            <x-multiline-field label="Sense Description" id="update_senseDescription" :value="$data->lu->senseDescription"></x-multiline-field>
            <x-combobox.fe-frame
                    id="update_incorporatedFE"
                    label="Incorporated FE"
                    :value="$data->lu->incorporatedFE"
                    :idFrame="$data->lu->idFrame"
                    :hasNull="true"
            ></x-combobox.fe-frame>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Update LU" hx-put="/lu/{{$data->lu->idLU}}"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
