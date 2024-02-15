<x-form-search id="reportFrameFormSearch">
    <input type="hidden" name="search_token" value="{{ csrf_token() }}" />
    <x-input-field id="search_frame" value="" placeholder="Search Frame"></x-input-field>
    <x-input-field id="search_fe"  value="" placeholder="Search FE"></x-input-field>
    <x-input-field id="search_lu"  value="" placeholder="Search LU"></x-input-field>
    <x-submit label="Search"  hx-post="/report/grid/frames" hx-target="#frameSlotGrid"></x-submit>
</x-form-search>
