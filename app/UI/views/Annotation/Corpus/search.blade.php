<x-form-search id="corpusFormSearch">
    <input type="hidden" name="search_token" value="{{ csrf_token() }}" />
    <x-input-field id="search_corpus" :value="$data->search->corpus ?? ''" placeholder="Search Corpus"></x-input-field>
    <x-input-field id="search_document"  :value="$data->search->document ?? ''" placeholder="Search Document"></x-input-field>
    <x-submit label="Search"  hx-post="/annotation/grid/corpus" hx-target="#annotationCorpusGrid"></x-submit>
    <x-input-field id="search_idSentence"  value="" placeholder="#ID Sentence"></x-input-field>
    <x-button id="btnAnnotate" label="Annotate Sentence"  color="secondary" hx-get="/annotation/grid/corpus"></x-button>
</x-form-search>
