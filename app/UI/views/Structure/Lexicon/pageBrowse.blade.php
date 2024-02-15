<x-layout.index>
    <x-layout.browser>
        <x-slot:title>
            @include('Structure.Lexicon.slotTitle')
        </x-slot:title>
        <x-slot:search>
            @include('Structure.Lexicon.slotSearch')
        </x-slot:search>
        <x-slot:grid>
            <div id="lexiconSlotGrid" class="h-full p-0 w-full">
                @include('Structure.Lexicon.slotGrid')
            </div>
        </x-slot:grid>
        <x-slot:footer></x-slot:footer>
    </x-layout.browser>
</x-layout.index>
