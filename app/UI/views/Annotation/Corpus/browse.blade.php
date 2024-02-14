<x-layout.index>
    <x-layout.browser>
        <x-slot:title>
            @include('Annotation.Corpus.title')
        </x-slot:title>
        <x-slot:search>
            @include('Annotation.Corpus.search')
        </x-slot:search>
        <x-slot:grid>
            <div id="annotationCorpusGrid" class="h-full p-0 w-full">
                @include('Annotation.Corpus.grid')
            </div>
        </x-slot:grid>
        <x-slot:footer></x-slot:footer>
    </x-layout.browser>
</x-layout.index>
