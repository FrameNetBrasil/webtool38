<x-layout.content>
    <x-form id="corpusDocumentFormNew" title="New Document" center="true">
        <x-slot:fields>
            <x-hidden-field id="new_idCorpus" :value="$data->idCorpus"></x-hidden-field>
            <x-text-field id="new_nameEn" label="English Name" value=""></x-text-field>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add Document" hx-post="/documents"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
