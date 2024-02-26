<x-layout.content>
    <x-form id="feRelationFormNew" title="New FrameElement Relation" center="true">
        <x-slot:fields>
            <x-hidden-field id="relation_idEntityRelation" :value="$idEntityRelation"></x-hidden-field>
            <x-combobox.fe-frame id="relation_idFrameElement" :idFrame="$frame->idFrame" label="{{$frame->name}}.FE"></x-combobox.fe-frame>
            <div class="mb-2 color_{{$relation->entry}}">{{$relation->name}}</div>
            <x-combobox.fe-frame id="relation_idFrameElementRelated" :idFrame="$relatedFrame->idFrame" label="{{$relatedFrame->name}}.FE"></x-combobox.fe-frame>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add Relation" hx-post="/relation/fe"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
