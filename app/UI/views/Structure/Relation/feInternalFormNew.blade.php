<x-layout.content>
    <x-form id="frameFERelationFormNew" title="New FrameElement Relation" center="true">
        <x-slot:fields>
            <x-checkbox.fe-frame id="idFrameElementRelated" :idFrame="$data->idFrame" label="Frame Elements"></x-checkbox.fe-frame>
            <x-combobox.relation id="relationType" group="fe"></x-combobox.relation>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add Relation" hx-post="/frame/{{$data->idFrame}}/feRelations"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
