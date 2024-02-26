@extends('Structure.Frame.main')
@section('content')
    <x-layout.browser>
        <x-slot:nav>
            <x-form-search id="frameSearch">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <x-input-field
                    id="search_frame"
                    value="{{$search->frame}}"
                    placeholder="Search Frame"
                ></x-input-field>
                <x-input-field
                    id="search_fe"
                    value="{{$search->fe}}"
                    placeholder="Search FE"
                ></x-input-field>
                <x-input-field
                    id="search_lu"
                    value="{{$search->lu}}"
                    placeholder="Search LU"
                ></x-input-field>
                <x-combobox.framal-domain
                    id="search_idFramalDomain"
                    placeholder="Domain"
                    value="{{$search->idFramalDomain}}"
                ></x-combobox.framal-domain>
                <x-combobox.framal-type
                    id="search_idFramalType"
                    placeholder="Type"
                    value="{{$search->idFramalType}}"
                ></x-combobox.framal-type>
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
