<x-layout.content>
    <x-form id="formAdd" title="Add SemanticType" center="true">
        <x-slot:fields>
            <x-combobox.semantic-type
                id="new_idSemanticType"
                label="Semantic Type"
                :root="$root"
            ></x-combobox.semantic-type>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit
                label="Add Semantic Type"
                hx-post="/semanticType/{{$idEntity}}/add"
            ></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
