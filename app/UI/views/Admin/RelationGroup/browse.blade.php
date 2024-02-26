@extends('Admin.RelationGroup.main')
@section('content')
    <x-layout.browser>
        <x-slot:nav>
            <x-form-search id="rgSearch">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <x-input-field id="search_relationGroup" :value="$search->relationGroup ?? ''"
                               placeholder="Search RelationGroup"></x-input-field>
                <x-input-field id="search_relationType" :value="$search->relationType ?? ''"
                               placeholder="Search RelationType"></x-input-field>
                <x-submit label="Search" hx-post="/relationgroup/grid" hx-target="#rgGrid"></x-submit>
            </x-form-search>
        </x-slot:nav>
        <x-slot:main>
            <div id="rgGrid" class="mainGrid">
                @include('Admin.RelationGroup.grid')
            </div>
        </x-slot:main>
    </x-layout.browser>
@endsection
