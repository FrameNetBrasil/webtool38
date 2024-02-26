<x-layout.content>
    <x-form id="entryForm" title="Translations" center="true">
        <x-slot:fields>
            @foreach($languages as $language)
                @php
                    $idLanguage = $language['idLanguage'];
                    debug($entries[$idLanguage]['description']);
                    $description = mb_ereg_replace("\r\n","\\n",$entries[$idLanguage]['description']);
                    debug($description);
                @endphp
                <x-card
                    title="{{$language['description']}}"
                    class="mb-4"
                >
                    <x-hidden-field
                        id="idEntry_{{$idLanguage}}"
                        :value="$entries[$idLanguage]['idEntry']"
                    ></x-hidden-field>
                    <x-text-field
                        label="Name"
                        id="name_{{$idLanguage}}"
                        :value="$entries[$idLanguage]['name']"
                    ></x-text-field>
                    <x-multiline-field
                        label="Definition"
                        id="description_{{$idLanguage}}"
                        value="{{$description}}"
                    >
                    </x-multiline-field>
                </x-card>
            @endforeach
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Save" hx-put="/entry"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
