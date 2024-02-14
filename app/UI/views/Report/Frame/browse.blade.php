<x-layout.index>
    <x-layout.browser>
        <x-slot:title>
            @include('Report.Frame.title')
        </x-slot:title>
        <x-slot:search>
            @include('Report.Frame.search')
        </x-slot:search>
        <x-slot:grid>
            <div id="framesGrid" class="h-full p-0 w-full">
                @include('Report.Frame.grid')
            </div>
        </x-slot:grid>
        <x-slot:footer></x-slot:footer>
    </x-layout.browser>
</x-layout.index>
