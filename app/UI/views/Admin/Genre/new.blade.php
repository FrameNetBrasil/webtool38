@extends('Admin.Genre.main')
@section('content')
    <div class="new">
        <x-form id="formNew" title="New Genre" center="True">
            <x-slot:fields>
                <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
            </x-slot:fields>
            <x-slot:buttons>
                <x-submit label="Add Genre" hx-post="/genre"></x-submit>
            <x-slot:buttons>
        </x-form>
    </div>
@endsection