<x-layout.index>
    <x-layout.edit>
        <x-slot:title>
            @include('Structure.Lexicon.slotTitle')
        </x-slot:title>
        <x-slot:menu>
            @include('Structure.Lexicon.slotMenu')
        </x-slot:menu>
        <x-slot:pane>
            <div id="lexiconEditPane">
            </div>
        </x-slot:pane>
        <x-slot:footer></x-slot:footer>
    </x-layout.edit>
</x-layout.index>
