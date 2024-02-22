@extends('Admin.RelationType.main')
@section('content')
    <div class="new">
        <x-form id="formNew" title="New RelationType" center="true">
            <x-slot:fields>
                <x-hidden-field id="new_idDomain" :value="1"></x-hidden-field>
                <x-combobox.relation-group id="new_idRelationGroup" label="RelationGroup"></x-combobox.relation-group>
                <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
            </x-slot:fields>
            <x-slot:buttons>
                <x-submit label="Add RelationType" hx-post="/relationtype/new"></x-submit>
            </x-slot:buttons>
        </x-form>
    </div>
@endsection
