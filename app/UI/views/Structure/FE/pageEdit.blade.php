<x-layout.content>
    <x-layout.edit>
        <x-slot:title>
            @include('Structure.FE.slotTitle')
        </x-slot:title>
        <x-slot:menu>
            @include('Structure.FE.slotMenu')
        </x-slot:menu>
        <x-slot:pane>
            <div id="feEditPane">
            </div>
        </x-slot:pane>
        <x-slot:footer></x-slot:footer>
    </x-layout.edit>
</x-layout.content>
