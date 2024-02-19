<x-layout.content>
    <x-form id="rtsFormEdit" title="Edit RelationType" center="true">
        <x-slot:fields>
            <x-hidden-field id="update_idRelationType" :value="$data->relationType->idRelationType"></x-hidden-field>
            <x-text-field id="update_prefix" label="Prefix" value=""></x-text-field>

        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Update RelationType" hx-put="/relationtype/{{$data->relationType->idRelationType}}"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
