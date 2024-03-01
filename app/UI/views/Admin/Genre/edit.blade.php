@extends('Admin.Genre.main')
@section('content')
    <x-layout.edit>
        <x-slot:edit>
            <div class="grid grid-nogutter editHeader">
                <div class="col-8 title">
                    <span class="color_generic">{{$data->genre?->name}}</span>
                </div>
                <div class="col-4 text-right description">
                    <x-tag label="#{{$data->genre->idGenre}}"></x-tag>
                    <x-button
                        label="Delete"
                        color="danger"
                        onclick="manager.confirmDelete(`Removing Genre') '{{$data->genre?->name}}'. Confirm?` , '/genre/{{$data->genre->idGenre}}')"
                    ></x-button>
                </div>
            </div>
            <div class="description">{{$data->genre?->description}}</div>
        </x-slot:edit>
        <x-slot:nav>
            <div class="options">
                <x-link-button
                    id="menuEntries"
                    label="Translations"
                    hx-get="/genre/{{$data->genre->idGenre}}/rts"
                    hx-target="#gPane"
                ><x-link-button>
                <x-link-button
                    id="menuG"
                    label="Genres"
                    hx-get="/genre/{{$data->genre->idGenre}}/rts"
                    hx-target="#gPane"
                ></x-link-button>
            </div>
        </x-slot:nav>
        <x-slot:main>
            <div id="gPane" class="mainPane">
            </div>
        <x-slot:main>
    </x-layout.edit>
@endsection

