<x-form-search id="corpusFormSearch">
    <input type="hidden" name="search_token" value="{{ csrf_token() }}" />
    <x-input-field id="search_corpus" :value="$search->corpus ?? ''" placeholder="Search Corpus"></x-input-field>
    <x-input-field id="search_document"  :value="$search->document ?? ''" placeholder="Search Document"></x-input-field>
    <x-input-field id="search_image"  :value="$search->image ?? ''" placeholder="Search Image"></x-input-field>
    <x-submit label="Search"  hx-post="/annotation/grid/staticFrameMode2" hx-target="#annotationStaticFrameMode2Grid"></x-submit>
</x-form-search>
