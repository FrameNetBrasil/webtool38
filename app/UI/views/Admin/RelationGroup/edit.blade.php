@extends('Admin.RelationGroup.main')
@section('content')
    <x-layout.edit>
        <x-slot:edit>
            <div class="grid grid-nogutter editHeader">
                <div class="col-8 title">
                    <span class="color_generic">{{$relationGroup?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <x-tag label="#{{$relationGroup->idRelationGroup}}"></x-tag>
                    <x-button
                        label="Delete"
                        color="danger"
                        onclick="manager.confirmDelete(`Removing RelationGroup '{{$relationGroup?->name}}'.`, '/relationgroup/{{$relationGroup->idRelationGroup}}')"
                    ></x-button>
                </div>
            </div>
            <div class="description">{{$relationGroup?->description}}</div>
        </x-slot:edit>
        <x-slot:nav>
            <div class="options">
                <x-link-button
                    id="menuEntries"
                    label="Translations"
                    hx-get="/relationgroup/{{$relationGroup->idRelationGroup}}/entries"
                    hx-target="#rgPane"
                ></x-link-button>
                <x-link-button
                    id="menuRT"
                    label="RelationTypes"
                    hx-get="/relationgroup/{{$relationGroup->idRelationGroup}}/rts"
                    hx-target="#rgPane"
                ></x-link-button>
            </div>
        </x-slot:nav>
        <x-slot:main>
            <div id="rgPane" class="mainPane">
            </div>
        </x-slot:main>
    </x-layout.edit>
@endsection
