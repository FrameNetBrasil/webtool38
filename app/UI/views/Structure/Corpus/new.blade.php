@extends('Structure.Corpus.main')
@section('content')
    <div class="new">
        <x-form id="formNew" title="New Corpus" center="true">
            <x-slot:fields>
                <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
            </x-slot:fields>
            <x-slot:buttons>
                <x-submit label="Add Corpus" hx-post="/corpus"></x-submit>
            </x-slot:buttons>
        </x-form>
    </div>
@endsection
