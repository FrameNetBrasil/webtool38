<x-layout.content>
    <x-form id="feFormEdit" title="Edit Frame Element" center="true">
        <x-slot:fields>
            <x-combobox.fe-coreness id="update_coreType" label="Coreness" :value="$data->frameElement->coreType"></x-combobox.fe-coreness>
            <x-combobox.color id="update_idColor" label="Color" :value="$data->frameElement->idColor"></x-combobox.color>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Update FE" hx-put="/fes/{{$data->frameElement->idFrameElement}}"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
