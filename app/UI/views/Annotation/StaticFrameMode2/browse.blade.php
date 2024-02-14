<x-layout.index>
    <x-layout.browser>
        <x-slot:title>
            @include('Annotation.StaticFrameMode2.title')
        </x-slot:title>
        <x-slot:search>
            @include('Annotation.StaticFrameMode2.search')
        </x-slot:search>
        <x-slot:grid>
            <div id="annotationStaticFrameMode2Grid" class="h-full p-0 w-full">
                @include('Annotation.StaticFrameMode2.grid')
            </div>
        </x-slot:grid>
        <x-slot:footer></x-slot:footer>
    </x-layout.browser>
</x-layout.index>
