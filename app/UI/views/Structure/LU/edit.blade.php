@extends('Structure.LU.main')
@section('content')
    <x-layout.edit>
        <x-slot:edit>
            <div class="grid grid-nogutter">
                <div class="col-8 title">
                    <span>LU: {{$data->lu?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <span>[{{$data->lu->frame->name}}]</span>
                    <span>[#{{$data->lu->idLU}}]</span>
                </div>
            </div>
        </x-slot:edit>
        <x-slot:nav>
            <div class="options">
                <x-link-button
                    id="menuLUEdit"
                    label="Edit"
                    hx-get="/lu/{{$data->lu->idLU}}/formEdit"
                    hx-target="#luPane"
                ></x-link-button>
                <x-link-button
                    id="menuLUConstraints"
                    label="Constraints"
                    hx-get="/lu/{{$data->lu->idLU}}/constraints"
                    hx-target="#luPane"
                ></x-link-button>
                <x-link-button
                    id="menuLUSemanticTypes"
                    label="SemanticTypes"
                    hx-get="/lu/{{$data->lu->idLU}}/semanticTypes"
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
