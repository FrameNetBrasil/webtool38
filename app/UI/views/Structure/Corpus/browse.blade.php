@extends('Structure.Corpus.main')
@section('content')
    <x-layout.browser>
        <x-slot:nav>
            <x-form-search id="corpusFormSearch">
                <input
                    type="hidden"
                    name="search_token"
                    value="{{ csrf_token() }}"
                />
                <x-input-field
                    id="search_corpus"
                    :value="$search->corpus ?? ''"
                    placeholder="Search Corpus">

                </x-input-field>
                <x-input-field
                    id="search_document"
                    :value="$search->document ?? ''"
                    placeholder="Search Document"
                ></x-input-field>
                <x-submit
                    label="Search"
                    hx-post="/corpus/grid"
                    hx-target="#corpusGrid"
                ></x-submit>
            </x-form-search>
        </x-slot:nav>
        <x-slot:main>
            <div id="corpusGrid" class="mainGrid">
                @include('Structure.Corpus.grid')
            </div>
        </x-slot:main>
    </x-layout.browser>
@endsection
