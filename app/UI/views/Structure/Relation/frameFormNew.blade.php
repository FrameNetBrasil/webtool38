<x-layout.content>
    <x-form
        id="frameRelationFormNew"
        title="New Frame Relation"
        center="true"
    >
        <x-slot:fields>
            <x-combobox.relation id="new_idRelationType" group="frame"></x-combobox.relation>
            <x-combobox.frame id="new_idFrameRelated" label="Related Frame"></x-combobox.frame>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add Relation" hx-post="/relation/frame"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
