@extends('Structure.Lemma.main')
@section('content')
    <div class="new">
        <x-form id="formNew" title="New Lemma" center="true">
            <x-slot:fields>
                <x-text-field
                    id="new_name"
                    label="Name"
                    value=""
                ></x-text-field>
                <x-combobox.pos
                    id="new_idPOS"
                    value=""
                    label="POS"
                ></x-combobox.pos>
                <x-combobox.language
                    id="new_idLanguage"
                    :value="$idLanguage"
                    label="Language"
                ></x-combobox.language>
            </x-slot:fields>
            <x-slot:buttons>
                <x-submit label="Add Lemma" hx-post="/lemma"></x-submit>
            </x-slot:buttons>
        </x-form>
    </div>
@endsection
