<x-layout.content>
    <x-layout.edit>
        <x-slot:title>
            @include('Structure.LU.slotTitle')
        </x-slot:title>
        <x-slot:menu>
            @include('Structure.LU.slotMenu')
        </x-slot:menu>
        <x-slot:pane>
            <div id="luEditPane">
            </div>
        </x-slot:pane>
        <x-slot:footer></x-slot:footer>
    </x-layout.edit>
</x-layout.content>
