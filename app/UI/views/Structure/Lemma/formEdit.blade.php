<x-layout.content>
    <x-form id="lemmaFormEdit" title="Edit Lemma" center="true">
        <x-slot:fields>
            <x-hidden-field id="update_idLemma" :value="$lemma->idLemma"></x-hidden-field>
            <x-text-field
                id="update_name"
                label="Name"
                :value="$lemma->name"
            ></x-text-field>
            <x-combobox.pos
                id="update_idPOS"
                :value="$lemma->idPOS"
                label="POS"
            ></x-combobox.pos>
            <x-combobox.language
                id="update_idLanguage"
                :value="$lemma->idLanguage"
                label="Language"
            ></x-combobox.language>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Update Lemma" hx-put="/lemma/{{$lemma->idLemma}}"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
