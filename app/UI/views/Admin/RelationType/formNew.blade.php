<x-layout.content>
    <x-form id="formNew" title="New RelationType" center="true">
        <x-slot:fields>
            <x-hidden-field id="new_idRelationGroup" :value="$idRelationGroup"></x-hidden-field>
            <x-hidden-field id="new_idDomain" :value="1"></x-hidden-field>
            <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add RelationType" hx-post="/relationtype"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
