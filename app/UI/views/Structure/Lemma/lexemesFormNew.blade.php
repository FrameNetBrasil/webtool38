<x-layout.content>
    <x-form id="lexemesFormNew" title="New Lexeme" center="true">
        <x-slot:fields>
            <x-hidden-field id="new_idLemma" :value="$idLemma"></x-hidden-field>
            <x-text-field id="new_nameEn" label="Lexeme" value=""></x-text-field>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add Lexeme" hx-post="/lemma/{{$idLemma}}/lexemes"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
