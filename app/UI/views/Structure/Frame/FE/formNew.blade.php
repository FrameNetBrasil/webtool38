<x-layout.content>
    <x-form id="formNew" title="New Frame Element" center="true">
        <x-slot:fields>
            <x-hidden-field id="new_idFrame" :value="$data->idFrame"></x-hidden-field>
            <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
            <x-combobox.fe-coreness id="new_coreType" label="Coreness"></x-combobox.fe-coreness>
            <x-combobox.color id="new_idColor" label="Color" value=""></x-combobox.color>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add FE" hx-post="/fes"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
