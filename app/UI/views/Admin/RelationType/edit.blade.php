@extends('Admin.RelationType.main')
@section('content')
    <x-layout.edit>
        <x-slot:edit>
            <div class="grid grid-nogutter editHeader">
                <div class="col-8 title">
                    <span class="color_generic">{{$data->relationType?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <x-tag label="{{$data->relationType->relationGroup->name}}"></x-tag>
                    <x-tag label="#{{$data->relationType->idRelationType}}"></x-tag>
                    @if(($data->_layout ?? '') == 'main')
                        <x-button
                            label="Delete"
                            color="danger"
                            onclick="manager.confirmDelete(`Removing RelationType '{{$data->relationType?->name}}'. Confirm?`, '/relationtype/{{$data->relationType->idRelationType}}/main')"
                        ></x-button>
                    @endif
                </div>
            </div>
            <div class="description">{{$data->relationType?->description}}</div>
        </x-slot:edit>
        <x-slot:nav>
            <div class="options">
                <x-link-button
                    id="menuRTEdit"
                    label="Edit"
                    hx-get="/relationtype/{{$data->relationType->idRelationType}}/formEdit"
                    hx-target="#rtPane"
                ></x-link-button>
                <x-link-button
                    id="menuRTEntries"
                    label="Translations"
                    hx-get="/relationtype/{{$data->relationType->idRelationType}}/entries"
                    hx-target="#rtPane"
                ></x-link-button>
            </div>
        </x-slot:nav>
        <x-slot:main>
            <div id="rtPane" class="mainPane">
            </div>
        </x-slot:main>
    </x-layout.edit>
@endsection
