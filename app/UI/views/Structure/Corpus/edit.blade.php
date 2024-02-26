@extends('Structure.Corpus.main')
@section('content')
    <x-layout.edit>
        <x-slot:edit>
            <div class="grid grid-nogutter editHeader">
                <div class="col-8 title">
                    <span class="color_corpus">{{$corpus?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    @if($isAdmin)
                        <x-button
                            label="Delete"
                            color="danger"
                            onclick="manager.confirmDelete(`Removing Corpus '{{$corpus?->name}}'. Confirm?`, '/corpus/{{$corpus->idCorpus}}')"
                        ></x-button>
                    @endif
                </div>
            </div>
        </x-slot:edit>
        <x-slot:nav>
            <div class="options">
                <x-link-button
                    id="menuEntries"
                    label="Translations"
                    hx-get="/corpus/{{$corpus->idCorpus}}/entries"
                    hx-target="#corpusPane"
                ></x-link-button>
                <x-link-button
                    id="menuDocuments"
                    label="Documents"
                    hx-get="/corpus/{{$corpus->idCorpus}}/documents"
                    hx-target="#corpusPane"
                ></x-link-button>
            </div>
        </x-slot:nav>
        <x-slot:main>
            <div id="corpusPane" class="mainPane">
            </div>
        </x-slot:main>
    </x-layout.edit>
@endsection
