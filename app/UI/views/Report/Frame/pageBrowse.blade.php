<x-layout.index>
    <x-layout.browser>
        <x-slot:title>
            @include('Report.Frame.slotTitle')
        </x-slot:title>
        <x-slot:search>
            @include('Report.Frame.slotSearch')
        </x-slot:search>
        <x-slot:grid>
            <div id="frameSlotGrid" class="h-full p-0 w-full">
                @include('Report.Frame.slotGrid')
            </div>
        </x-slot:grid>
        <x-slot:footer></x-slot:footer>
    </x-layout.browser>
</x-layout.index>
