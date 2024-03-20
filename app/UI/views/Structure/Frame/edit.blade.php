<x-layout.edit>
    <x-slot:title>
        Frames
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="secondary" href="/frame"></x-button>
    </x-slot:actions>
    <x-slot:name>
        {{$frame?->name}}
    </x-slot:name>
    <x-slot:detail>
        @foreach ($classification as $name => $values)
            @foreach ($values as $value)
                <x-tag label="{{$value}}"></x-tag>
            @endforeach
        @endforeach
        @if($isAdmin)
            <x-button
                label="Delete"
                color="danger"
                onclick="manager.confirmDelete(`Removing Frame '{{$frame?->name}}'. Confirm?`, '/frame/{{$frame->idFrame}}')"
            ></x-button>
        @endif
    </x-slot:detail>
    <x-slot:description>
        {{$frame?->description}}
    </x-slot:description>
    <x-slot:edit>
        <div id="editPanel" class="hxRow hxGutterless">
            <div
                class="hxCol hxSpan-12-xs hxSpan-12-sm hxSpan-2-md hxSpan-2-lg hxSpan-2-xl">
                <hx-disclosure aria-controls="editTranslations">
                    <a hx-get="/frame/{{$frame->idFrame}}/entries"
                       hx-target="#editTranslations"
                    >Translations
                    </a>

                </hx-disclosure>
            </div>
            <div
                class="hxCol hxSpan-12-xs hxSpan-12-sm hxSpan-10-md hxSpan-10-lg hxSpan-10-xl">
                <hx-reveal id="editTranslations">

                </hx-reveal>
                </div>
            </div>
        </div>
    </x-slot:edit>
</x-layout.edit>
