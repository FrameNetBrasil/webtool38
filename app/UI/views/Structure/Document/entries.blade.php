<x-layout.content>
    <x-form id="documentEntriesForm" title="Translations" center="true" >
        <x-slot:fields>
            @foreach($data->languages as $language)
                @php($idLanguage = $language['idLanguage'])
                <x-card title="{{$language['description']}}" class="mb-4">
                    <x-hidden-field id="idEntry_{{$idLanguage}}" :value="$data->entries[$idLanguage]['idEntry']"></x-hidden-field>
                    <x-text-field label="Name" id="name_{{$idLanguage}}"
                                  :value="$data->entries[$idLanguage]['name']"></x-text-field>
                    <x-multiline-field label="Definition" id="description_{{$idLanguage}}"
                                  :value="$data->entries[$idLanguage]['description']">
                    </x-multiline-field>
                </x-card>
            @endforeach
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Save" hx-put="/document/{{$data->document->getId()}}/entries"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
