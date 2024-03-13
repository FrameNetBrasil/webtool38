@extends('Structure.Corpus.main')
@section('content')
    <div class="new">
        <x-form id="formNew" center="true">
            <x-slot:header>
                <h2 x-text="Object"></h2>
            </x-slot:header>
            <x-slot:fields>
                <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
            </x-slot:fields>
            <x-slot:buttons>
                <x-submit label="Add Corpus" hx-post="/corpus"></x-submit>
            </x-slot:buttons>
        </x-form>
    </div>
@endsection
