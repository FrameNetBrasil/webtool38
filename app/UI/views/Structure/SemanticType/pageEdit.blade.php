<x-layout.index>
    <x-layout.edit>
        <x-slot:title>
            @include('Structure.Frame.slotTitle')
        </x-slot:title>
        <x-slot:menu>
            @include('Structure.Frame.slotMenu')
        </x-slot:menu>
        <x-slot:pane>
            <div id="frameEditPane">
            </div>
        </x-slot:pane>
        <x-slot:footer></x-slot:footer>
    </x-layout.edit>
</x-layout.index>
