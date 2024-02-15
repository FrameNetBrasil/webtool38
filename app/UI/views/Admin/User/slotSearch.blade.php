<x-form-search id="userFormSearch">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <x-input-field id="search_login" :value="$data->search->login ?? ''" placeholder="Search Login"></x-input-field>
    <x-input-field id="search_email" :value="$data->search->email ?? ''" placeholder="Search Email"></x-input-field>
    <x-submit label="Search"  hx-post="/users/grid" hx-target="#userGrid"></x-submit>
</x-form-search>

