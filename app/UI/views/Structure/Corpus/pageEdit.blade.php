<x-layout.index>
    <x-layout.edit>
        <x-slot:title>
            @include('Structure.Corpus.slotTitle')
        </x-slot:title>
        <x-slot:menu>
            @include('Structure.Corpus.slotMenu')
        </x-slot:menu>
        <x-slot:pane>
            <div id="corpusEditPane">
            </div>
        </x-slot:pane>
        <x-slot:footer></x-slot:footer>
    </x-layout.edit>
</x-layout.index>
