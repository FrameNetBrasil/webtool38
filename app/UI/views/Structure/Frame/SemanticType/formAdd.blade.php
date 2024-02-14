<x-layout.content>
    <x-form id="stFormAdd" title="Add SemanticType" center="true">
        <x-slot:fields>
            <x-combobox.semantic-type id="new_idSemanticType" label="Semantic Type" root="@framal_type"></x-combobox.semantic-type>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add Semantic Type" hx-post="/frames/{{$data->idFrame}}/semanticTypes"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>