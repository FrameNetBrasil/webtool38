<x-form-search id="corpusFormSearch">
    <input type="hidden" name="search_token" value="{{ csrf_token() }}" />
    <x-input-field id="search_corpus" :value="$data->search->corpus ?? ''" placeholder="Search Corpus"></x-input-field>
    <x-input-field id="search_document"  :value="$data->search->document ?? ''" placeholder="Search Document"></x-input-field>
    <x-submit label="Search"  hx-post="/corpus/grid" hx-target="#corpusSlotGrid"></x-submit>
    <x-button label="New Corpus" color="secondary" href="/corpus/new"></x-button>
</x-form-search>
