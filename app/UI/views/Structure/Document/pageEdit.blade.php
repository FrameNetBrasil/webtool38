<x-layout.content>
    <x-layout.edit>
        <x-slot:title>
            @include('Structure.Document.slotTitle')
        </x-slot:title>
        <x-slot:menu>
            @include('Structure.Document.slotMenu')
        </x-slot:menu>
        <x-slot:pane>
            <div id="documentEditPane">
            </div>
        </x-slot:pane>
        <x-slot:footer></x-slot:footer>
    </x-layout.edit>
</x-layout.content>
