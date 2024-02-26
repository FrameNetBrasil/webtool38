@extends('Structure.LU.main')
@section('content')
    <x-layout.edit>
        <x-slot:edit>
            <div class="grid grid-nogutter editHeader">
                <div class="col-8 title">
                    <span class="color_lexicon">{{$lu?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <x-tag label="{{$lu->frame->name}}"></x-tag>
                    <x-tag label="#{{$lu->idLU}}"></x-tag>
                </div>
            </div>
        </x-slot:edit>
        <x-slot:nav>
            <div class="options">
                <x-link-button
                    id="menuLUEdit"
                    label="Edit"
                    hx-get="/lu/{{$lu->idLU}}/formEdit"
                    hx-target="#luPane"
                ></x-link-button>
                <x-link-button
                    id="menuLUConstraints"
                    label="Constraints"
                    hx-get="/lu/{{$lu->idLU}}/constraints"
                    hx-target="#luPane"
                ></x-link-button>
                <x-link-button
                    id="menuLUSemanticTypes"
                    label="SemanticTypes"
                    hx-get="/lu/{{$lu->idLU}}/semanticTypes"
                    hx-target="#luPane"
                ></x-link-button>
            </div>
        </x-slot:nav>
        <x-slot:main>
            <div id="luPane" class="mainPane">
            </div>
        </x-slot:main>
    </x-layout.edit>
@endsection
