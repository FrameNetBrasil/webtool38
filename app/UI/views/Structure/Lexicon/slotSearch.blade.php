<x-form-search id="frameSlotSearch">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <x-input-field id="search_lemma" :value="$data->search->lemma ?? ''" placeholder="Search Lemma"></x-input-field>
    <x-input-field id="search_lexeme"  :value="$data->search->lexeme ?? ''" placeholder="Search Lexeme"></x-input-field>
    <x-combobox.language id="search_idLanguage"  :value="$data->search->idLanguage ?? ''" placeholder="Select Language"></x-combobox.language>
    <x-submit label="Search"  hx-post="/lexicon/grid" hx-target="#lexiconSlotGrid"></x-submit>
    <x-button label="New Lemma" color="secondary" href="/lemma/new"></x-button>
</x-form-search>
