<x-layout.index>
    <x-layout.browser>
        <x-slot:title>
            @include('Structure.Corpus.slotTitle')
        </x-slot:title>
        <x-slot:search>
            @include('Structure.Corpus.slotSearch')
        </x-slot:search>
        <x-slot:grid>
            <div id="corpusSlotGrid" class="h-full p-0 w-full">
                @include('Structure.Corpus.slotGrid')
            </div>
        </x-slot:grid>
        <x-slot:footer></x-slot:footer>
    </x-layout.browser>
</x-layout.index>
