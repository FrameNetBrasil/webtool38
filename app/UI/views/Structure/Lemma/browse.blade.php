@extends('Structure.Lemma.main')
@section('content')
    <x-layout.browser>
        <x-slot:nav>
            <x-form-search id="lemmaSearch">
                <input
                    type="hidden"
                    name="_token"
                    value="{{ csrf_token() }}"
                />
                <x-input-field
                    id="search_lemma"
                    :value="$search->lemma ?? ''"
                    placeholder="Lemma (min 3 chars)"
                ></x-input-field>
                <x-combobox.language
                    id="search_idLanguage"
                    :value="$data->search->idLanguage ?? ''"
                    placeholder="Select Language"
                ></x-combobox.language>
                <x-submit
                    label="Search"
                    hx-post="/lemma/grid"
                    hx-target="#lemmaGrid"
                ></x-submit>
            </x-form-search>
        </x-slot:nav>
        <x-slot:main>
            <div id="lemmaGrid" class="mainGrid">
                @include('Structure.Lemma.grid')
            </div>
        </x-slot:main>
    </x-layout.browser>
@endsection
