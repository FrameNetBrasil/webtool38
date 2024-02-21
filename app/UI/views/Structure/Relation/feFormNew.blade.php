<x-layout.content>
    <x-form id="feRelationFormNew" title="New FrameElement Relation" center="true">
        <x-slot:fields>
            <x-combobox.fe-frame id="relation_idFrameElement" :idFrame="$data->frame->idFrame" label="{{$data->frame->name}}.FE"></x-combobox.fe-frame>
            <div class="mb-2 color_{{$data->relation->entry}}">{{$data->relation->name}}</div>
            <x-combobox.fe-frame id="relation_idFrameElementRelated" :idFrame="$data->relatedFrame->idFrame" label="{{$data->relatedFrame->name}}.FE"></x-combobox.fe-frame>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add Relation" hx-post="/fes/{{$data->idEntityRelation}}/relations"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
