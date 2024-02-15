<x-layout.index>
    <x-layout.browser>
        <x-slot:title>
            @include('Structure.Frame.slotTitle')
        </x-slot:title>
        <x-slot:search>
            @include('Structure.Frame.slotSearch')
        </x-slot:search>
        <x-slot:grid>
            <div id="frameSlotGrid" class="h-full p-0 w-full">
                @include('Structure.Frame.slotGrid')
            </div>
        </x-slot:grid>
        <x-slot:footer></x-slot:footer>
    </x-layout.browser>
</x-layout.index>
