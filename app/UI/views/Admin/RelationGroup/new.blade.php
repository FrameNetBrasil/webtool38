@extends('Admin.RelationGroup.main')
@section('content')
    <div class="new">
        <x-form id="formNew" title="New RelationGroup" center="true">
            <x-slot:fields>
                <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
            </x-slot:fields>
            <x-slot:buttons>
                <x-submit label="Add RelationGroup" hx-post="/relationgroup"></x-submit>
            </x-slot:buttons>
        </x-form>
    </div>
@endsection
