@extends('Structure.FE.main')
@section('content')
    <x-layout.edit>
        <x-slot:edit>
            <div class="grid grid-nogutter">
                <div class="col-8 title">
                    <span class="color_frame">{{$data->frameElement->frame->name}}.{{$data->frameElement?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <x-tag label="{{$data->frameElement->frame->name}}"></x-tag>
                    <x-tag label="#{{$data->frameElement->idFrameElement}}"></x-tag>
                </div>
            </div>
        </x-slot:edit>
        <x-slot:nav>
            <div class="options">
                <x-link-button
                    id="menuFEEdit"
                    label="Edit"
                    hx-get="/fe/{{$data->frameElement->idFrameElement}}/formEdit"
                    hx-target="#fePane"
                ></x-link-button>
                <x-link-button
                    id="menuFEEntries"
                    label="Translations"
                    hx-get="/fe/{{$data->frameElement->idFrameElement}}/entries"
                    hx-target="#fePane"
                ></x-link-button>
                <x-link-button
                    id="menuFEConstraints"
                    label="Constraints"
                    hx-get="/fe/{{$data->frameElement->idFrameElement}}/constraints"
                    hx-target="#fePane"
                ></x-link-button>
                <x-link-button
                    id="menuFESemanticTypes"
                    label="SemanticTypes"
                    hx-get="/fe/{{$data->frameElement->idFrameElement}}/semanticTypes"
                    hx-target="#fePane"
                ></x-link-button>
            </div>
        </x-slot:nav>
        <x-slot:main>
            <div id="fePane" class="mainPane">
            </div>
        </x-slot:main>
    </x-layout.edit>
@endsection
