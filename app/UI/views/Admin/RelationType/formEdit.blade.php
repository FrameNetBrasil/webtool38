<x-layout.content>
    <x-form id="rtsFormEdit" title="Edit RelationType" center="true">
        <x-slot:fields>
            <x-hidden-field id="update_idDomain" :value="1"></x-hidden-field>
            <x-hidden-field id="update_idRelationType" :value="$data->relationType->idRelationType"></x-hidden-field>
            <x-combobox.relation-group
                id="update_idRelationGroup"
                label="RelationGroup"
                :value="$data->relationType->idRelationGroup"
            ></x-combobox.relation-group>
            <x-text-field id="update_prefix" label="Prefix" :value="$data->relationType->prefix"></x-text-field>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Update RelationType" hx-put="/relationtype/{{$data->relationType->idRelationType}}"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
