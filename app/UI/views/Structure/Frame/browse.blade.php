@extends('Structure.Frame.main')
@section('content')
    <x-layout.browser>
        <x-slot:nav>
            <x-form-search id="frameSearch">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <x-input-field
                    id="search_frame"
                    value="{{$data->search->frame}}"
                    placeholder="Search Frame"
                ></x-input-field>
                <x-input-field
                    id="search_fe"
                    value="{{$data->search->fe}}"
                    placeholder="Search FE"
                ></x-input-field>
                <x-input-field
                    id="search_lu"
                    value="{{$data->search->lu}}"
                    placeholder="Search LU"
                ></x-input-field>
                <x-combobox.frame-classification
                    id="search_listBy"
                    placeholder="List by"
                    value="{{$data->search->listBy}}"
                ></x-combobox.frame-classification>
                <x-submit
                    label="Search"
                    hx-post="/frame/grid"
                    hx-target="#frameGrid"
                ></x-submit>
            </x-form-search>
        </x-slot:nav>
        <x-slot:main>
            <div id="frameGrid" class="mainGrid">
                @include('Structure.Frame.grid')
            </div>
        </x-slot:main>
    </x-layout.browser>
@endsection
