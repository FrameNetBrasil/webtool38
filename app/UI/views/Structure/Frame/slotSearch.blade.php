<x-form-search id="frameSlotSearch">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <x-combobox.domain id="search_idDomain" value="" placeholder="Domain"></x-combobox.domain>
    <x-input-field id="search_frame" :value="$data->search->frame ?? ''" placeholder="Search Frame"></x-input-field>
    <x-input-field id="search_fe"  :value="$data->search->fe ?? ''" placeholder="Search FE"></x-input-field>
    <x-input-field id="search_lu"  :value="$data->search->lu ?? ''" placeholder="Search LU"></x-input-field>
    <x-combobox.frame-classification id="search_listBy" placeholder="List by" value=""></x-combobox.frame-classification>
    <x-submit label="Search"  hx-post="/frames/grid" hx-target="#frameSlotGrid"></x-submit>
    <x-button label="New Frame" color="secondary" href="/frames/new"></x-button>
</x-form-search>
