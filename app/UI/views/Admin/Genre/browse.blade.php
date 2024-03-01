@extends('Admin.Genre.main')
@section('content')
    <x-layout.browser>
        <x-slot:nav>
            <x-form-search id="gSearch">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <x-input-field id="search_genre" :value="$search->genre ?? ''"
                    placeholder="Search Genre"></x-input-field>
                <x-submit label="Search" hx-post="/genre/grid" hx-target="#gGrid"></x-submit>
            </x-form-search>
        </x-slot:nav>
        <x-slot:main>
            <div id="gGrid" class="mainGrid">
                @include('Admin.genre.grid')
            </div>
        </x-slot:main>
    </x-layout.browser>
@endsection
