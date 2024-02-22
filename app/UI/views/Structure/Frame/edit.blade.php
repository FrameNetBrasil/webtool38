@extends('Structure.Frame.main')
@section('content')
    <x-layout.edit>
        <x-slot:edit>
            <div class="grid grid-nogutter editHeader">
                <div class="col-8 title">
                    <span class="color_frame">{{$data->frame?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    @foreach ($data->classification as $name => $values)
                        @foreach ($values as $value)
                            <x-tag label="{{$value}}"></x-tag>
                        @endforeach
                    @endforeach
                    @if($data->isAdmin)
                        <x-button
                            label="Delete"
                            color="danger"
                            onclick="manager.confirmDelete(`Removing Frame '{{$data->frame?->name}}'. Confirm?`, '/frame/{{$data->frame->idFrame}}')"
                        ></x-button>
                    @endif
                </div>
            </div>
            <div class="description">{{$data->frame?->description}}</div>
        </x-slot:edit>
        <x-slot:nav>
            <div class="options">
                <x-link-button
                    id="menuEntries"
                    label="Translations"
                    hx-get="/frame/{{$data->frame->idFrame}}/entries"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuFE"
                    label="FrameElements"
                    hx-get="/frame/{{$data->frame->idFrame}}/fes"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuNewLU"
                    label="LUs"
                    hx-get="/frame/{{$data->frame->idFrame}}/lus"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuClassification"
                    label="Classification"
                    hx-get="/frame/{{$data->frame->idFrame}}/classification"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuRelations"
                    label="Relations"
                    hx-get="/frame/{{$data->frame->idFrame}}/relations"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuFERelations"
                    label="FE-Relations"
                    hx-get="/frame/{{$data->frame->idFrame}}/feRelations"
                    hx-target="#framePane"
                ></x-link-button>
                <x-link-button
                    id="menuSemanticTypes"
                    label="SemanticTypes"
                    hx-get="/frame/{{$data->frame->idFrame}}/semanticTypes"
                    hx-target="#framePane"
                ></x-link-button>
            </div>
        </x-slot:nav>
        <x-slot:main>
            <div id="framePane" class="mainPane">
            </div>
        </x-slot:main>
    </x-layout.edit>
@endsection
