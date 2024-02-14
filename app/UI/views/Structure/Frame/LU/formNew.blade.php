<x-layout.content>
    <x-form id="frameLUFormNew" title="New LU" center="true">
        <x-slot:fields>
            <x-hidden-field id="new_idFrame" :value="$data->idFrame"></x-hidden-field>
            <x-combobox.lemma id="new_idLemma" label="Lemma" value=""></x-combobox.lemma>
            <x-multiline-field label="Sense Description" id="new_senseDescription" value=""></x-multiline-field>
            <x-combobox.fe-frame
                    id="new_incorporatedFE"
                    label="Incorporated FE"
                    :idFrame="$data->idFrame"
                    :hasNull="true"
            ></x-combobox.fe-frame>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add LU" hx-post="/lus"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
