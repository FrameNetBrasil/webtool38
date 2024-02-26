<x-layout.content>
    <x-form
        id="frameRelationFormNew"
        title="New Frame Relation"
        center="true"
    >
        <x-slot:fields>
            <x-hidden-field id="new_idFrame" :value="$idFrame"></x-hidden-field>
            <x-combobox.relation id="new_relationType" group="frame"></x-combobox.relation>
            <x-combobox.frame id="new_idFrameRelated" label="Related Frame"></x-combobox.frame>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add Relation" hx-post="/relation/frame"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
