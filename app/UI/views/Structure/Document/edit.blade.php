@extends('Structure.Document.main')
@section('content')
    <x-layout.edit>
        <x-slot:edit>
            <div class="grid grid-nogutter">
                <div class="col-8 title">
                    <span class="color_corpus">{{$document->corpus->name}}.{{$document?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <x-tag label="{{$document->corpus->name}}"></x-tag>
                    <x-tag label="#{{$document->idDocument}}"></x-tag>
                </div>
            </div>
        </x-slot:edit>
        <x-slot:nav>
            <div class="options">
                <x-link-button
                    id="menuDocumentEntries"
                    label="Translations"
                    hx-get="/document/{{$document->idDocument}}/entries"
                    hx-target="#documentPane"
                ></x-link-button>
            </div>
        </x-slot:nav>
        <x-slot:main>
            <div id="documentPane" class="mainPane">
            </div>
        </x-slot:main>
    </x-layout.edit>
@endsection
