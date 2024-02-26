@extends('Structure.Lemma.main')
@section('content')
    <x-layout.edit>
        <x-slot:edit>
            <div class="grid grid-nogutter editHeader">
                <div class="col-8 title">
                    <span class="color_lemma">{{$lemma?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <x-tag label="{{$lemma->language->description}}"></x-tag>
                    <x-tag label="#{{$lemma->idLemma}}"></x-tag>
                    @if($isAdmin)
                        <x-button
                            label="Delete"
                            color="danger"
                            onclick="manager.confirmDelete(`Removing Lemma '{{$lemma?->name}}'.`, '/lemma/{{$lemma->idLemma}}')"
                        ></x-button>
                    @endif
                </div>
            </div>
        </x-slot:edit>
        <x-slot:nav>
            <div class="options">
                <x-link-button
                    id="menuEdit"
                    label="Edit"
                    hx-get="/lemma/{{$lemma->idLemma}}/edit"
                    hx-target="#lemmaPane"
                ></x-link-button>
                <x-link-button
                    id="menuLexeme"
                    label="Lexemes"
                    hx-get="/lemma/{{$lemma->idLemma}}/lexemes"
                    hx-target="#lemmaPane"
                ></x-link-button>
            </div>
        </x-slot:nav>
        <x-slot:main>
            <div id="lemmaPane" class="mainPane">
            </div>
        </x-slot:main>
    </x-layout.edit>
@endsection
